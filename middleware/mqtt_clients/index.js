const mqtt = require('mqtt');
require('dotenv').config({ path: '../../.env' });
const { io } = require("socket.io-client");
const axios = require('axios'); // Import axios for HTTP requests

// Initialize a variable to store fetched devices. This array will hold the device configurations.
let devices = [];

// A Map to store the latest processed data for each device,
// which will be saved to the database at intervals.
// The key will be the deviceNameFromApi, and the value will be the payloadForDb.
const dataBuffer = new Map();

// Socket.IO client configuration for connecting to your WebSocket server.
const socket = io(process.env.WEBSOCKET_URL || 'http://localhost:3030', {
    transports: ['websocket'], // Force WebSocket transport
    reconnection: true,        // Enable auto-reconnection
    reconnectionAttempts: 5,   // Number of reconnection attempts
    reconnectionDelay: 1000,   // Delay between reconnection attempts in ms
    timeout: 20000             // Connection timeout in ms
});

// MQTT broker configuration, typically from environment variables.
const brokerUrl = process.env.MQTT_BROKER_URL || 'mqtt://localhost:1883';
const topic = process.env.MQTT_TOPIC; // The MQTT topic to subscribe to

// Create and connect the MQTT client to the broker.
const client = mqtt.connect(brokerUrl);

/**
 * Fetches device data from your API.
 * This data is crucial for filtering incoming MQTT messages and populating the 'key' column.
 */
async function getDevices() {
    try {
        const response = await axios.get(`${process.env.API_URL}/api/get-devices`);
        console.log('Fetched device data:', response.data);
        // Store the fetched devices in the global 'devices' array.
        devices = response.data;
        // Emit the raw fetched device data to the WebSocket clients.
        socket.emit('deviceData', devices);
    } catch (error) {
        console.error('Error fetching device data:', error.message);
    }
}

/**
 * Saves all currently buffered data to the database via API.
 * This function is called periodically and on application shutdown.
 */
async function saveBufferedDataToDb() {
    if (dataBuffer.size === 0) {
        console.log('No data in buffer to save to database.');
        return;
    }

    console.log(`Saving ${dataBuffer.size} buffered data entries to database...`);
    const promises = [];
    for (const [deviceNameFromApi, payloadForDb] of dataBuffer.entries()) {
        promises.push(
            axios.post(`${process.env.API_URL}/api/history-logs`, payloadForDb)
                .then(dbResponse => {
                    console.log(`Successfully sent history log for key "${deviceNameFromApi}":`, payloadForDb);
                    console.log('Database API response:', dbResponse.data);
                })
                .catch(dbError => {
                    console.error(`Error sending history log for key "${deviceNameFromApi}":`, dbError.message);
                    if (dbError.response) {
                        console.error('DB API Error Response Data:', dbError.response.data);
                        console.error('DB API Error Response Status:', dbError.response.status);
                        console.error('DB API Error Response Headers:', dbError.response.headers);
                    }
                })
        );
    }

    // Wait for all database save operations to complete
    await Promise.allSettled(promises);
    console.log('All buffered data saving attempts completed.');
    // Clear the buffer after attempting to save all data
    dataBuffer.clear();
}

// --- Logic for timed database saving ---
let saveIntervalId; // Store the interval ID to clear it later

function initializeTimedSave() {
    const intervalMs = 60 * 1000; // 1 menit
    const now = new Date();
    const seconds = now.getSeconds();
    const milliseconds = now.getMilliseconds();

    const totalMsIntoCurrentMinute = seconds * 1000 + milliseconds;
    let initialDelay = intervalMs - totalMsIntoCurrentMinute;

    if (initialDelay <= 0) {
        initialDelay += intervalMs;
    }

    console.log(`Initial database save will occur in ${initialDelay / 1000} seconds, at the next 1-minute mark.`);

    setTimeout(() => {
        saveBufferedDataToDb();
        saveIntervalId = setInterval(saveBufferedDataToDb, intervalMs);
        console.log(`Recurring database save interval set to ${intervalMs / 1000} seconds.`);
    }, initialDelay);
}


// Call this function to start the timed save process
initializeTimedSave();


// --- MQTT Client Event Handlers ---

// Event handler for successful MQTT broker connection.
client.on('connect', () => {
    console.log('Connected to MQTT broker');
    // Subscribe to the defined MQTT topic.
    client.subscribe(topic, (err) => {
        if (err) {
            console.error('Failed to subscribe to topic:', err);
        } else {
            console.log(`Subscribed to topic: ${topic}`);
        }
    });

    // Immediately fetch device data from the API once connected to MQTT.
    getDevices();
});

// Event handler for incoming messages from the MQTT broker.
// This callback is now 'async' to allow for awaiting API requests.
client.on('message', async (topic, message) => {
    const rawData = message.toString();
    console.log(`Received message on topic ${topic}:`, rawData);

    try {
        const parsedMqttData = JSON.parse(rawData);

        // Assume the MQTT message payload always contains an array as the value
        // of its first (and likely only) top-level key (e.g., "DataTRB245").
        const mqttDataArray = Object.values(parsedMqttData)[0];

        // Validate if the extracted data is indeed an array.
        if (!Array.isArray(mqttDataArray)) {
            console.warn('MQTT message payload does not contain an array as expected:', parsedMqttData);
            // If not an array, emit the raw parsed data without filtering.
            socket.emit('realtime', { topic, data: parsedMqttData });
            return;
        }

        // Proceed with filtering only if device configurations have been loaded.
        if (devices.length > 0) {
            let matchedDataCount = 0; // To track if any device found a match

            // Use 'for...of' loop to allow 'await' inside the loop for API calls.
            for (const device of devices) {
                // The 'name' from API (e.g., "1", "2") is used for filtering and for the 'key' column.
                const deviceNameFromApi = device.name;
                // 'display_name' (e.g., "FM1", "FM2") for better frontend display.
                const deviceDisplayName = device.display_name;

                // Filter the MQTT data array to find items that match this device.
                // A match occurs if the MQTT item's 'server_name' property matches the device's API 'name'.
                const filteredMqttDataForDevice = mqttDataArray.filter(mqttItem =>
                    mqttItem.server_name === deviceNameFromApi
                );

                // If matching data is found for this device, process it.
                if (filteredMqttDataForDevice.length > 0) {
                    matchedDataCount++;
                    console.log(`Matched data for device API Name: "${deviceNameFromApi}" (Display Name: "${deviceDisplayName}"):`, filteredMqttDataForDevice);

                    // Prepare the payload for insertion into the 'history_logs' database.
                    // This payload will be buffered.
                    let payloadForDb = {
                        key: deviceNameFromApi, // Populate 'key' with the 'name' from get-devices API
                        flowmeter: null,
                        totalizer: null,
                        velocity: null       // Kolom 'velocity' digunakan kembali
                    };

                    // Populate the payload from the filtered MQTT data.
                    filteredMqttDataForDevice.forEach(item => {
                        if (item.name && item.name.includes('Flowrate')) {
                            payloadForDb.flowmeter = parseFloat(item.data);
                        } else if (item.name && item.name.includes('Totalizer')) {
                            payloadForDb.totalizer = parseFloat(item.data);
                        } else if (item.name && item.name.includes('Velocity')) {
                            payloadForDb.velocity = parseFloat(item.data);
                        }
                    });
                    console.log('Constructed payloadForDb:', payloadForDb);

                    // Add or update the latest payload for this device in the buffer.
                    dataBuffer.set(deviceNameFromApi, payloadForDb);
                    console.log(`Buffered latest data for key "${deviceNameFromApi}". Current buffer size: ${dataBuffer.size}`);

                    // Emit filtered data via WebSocket for real-time frontend updates immediately.
                    socket.emit('realtime', {
                        key: deviceNameFromApi,      // Send the device 'name' as key
                        deviceName: deviceDisplayName, // Send display name for better UI presentation
                        topic: topic,
                        data: filteredMqttDataForDevice // Still emit original filtered array for UI
                    });
                }
            }

            // If no specific device match was found for the entire MQTT message,
            // emit the raw parsed data to the 'realtime' event.
            if (matchedDataCount === 0) {
                console.log('No specific device match found for this MQTT message. Emitting raw parsed data.');
                socket.emit('realtime', { topic, data: parsedMqttData });
            }

        } else {
            // If device configurations haven't been loaded yet, emit the raw parsed MQTT data.
            console.log('Devices not yet loaded, emitting raw parsed MQTT data.');
            socket.emit('realtime', { topic, data: parsedMqttData });
        }

    } catch (e) {
        console.error('Failed to parse MQTT message as JSON or process data:', e.message);
        // If JSON parsing fails or other processing errors occur, emit the original raw string data.
        socket.emit('realtime', { topic, data: rawData });
    }
});

// Event handler for MQTT broker disconnection.
client.on('disconnect', () => {
    console.log('Disconnected from MQTT broker');
});

// Event handler for MQTT client errors.
client.on('error', (err) => {
    console.error('MQTT Error:', err);
});

// --- Application Shutdown Handling ---

// Graceful shutdown on SIGINT (Ctrl+C)
process.on('SIGINT', async () => {
    console.log('\nReceived SIGINT. Initiating graceful shutdown...');
    // Clear the interval to prevent new saves
    if (saveIntervalId) {
        clearInterval(saveIntervalId);
    }

    // Attempt to save any remaining buffered data before exiting
    if (dataBuffer.size > 0) {
        console.log('Flushing remaining buffered data to database...');
        await saveBufferedDataToDb(); // Await the flush operation
    }

    // Ensure the MQTT client disconnects cleanly.
    if (client && client.connected) {
        console.log('Disconnecting from MQTT broker...');
        client.end(() => {
            console.log('MQTT client disconnected.');
        });
    }
    // Disconnect the Socket.IO client if it's still connected.
    if (socket && socket.connected) {
        console.log('Disconnecting Socket.IO client...');
        socket.disconnect();
    }

    console.log('Shutdown complete. Exiting process.');
    process.exit(0); // Exit cleanly
});

// General process exit handler (less graceful for unexpected exits)
process.on('exit', (code) => {
    console.log(`Process exited with code: ${code}`);
    // This handler runs after other handlers and usually after event loop is empty.
    // Buffered data flush should ideally happen in SIGINT/SIGTERM handlers.
});