// Pastikan untuk menginstal paket mqtt: npm install mqtt

const mqtt = require('mqtt');

/**
 * Menghasilkan nilai numerik acak dalam rentang tertentu.
 * @param {number} min - Nilai minimum.
 * @param {number} max - Nilai maksimum.
 * @param {number} decimalPlaces - Jumlah angka di belakang koma.
 * @returns {string} Nilai acak sebagai string dengan format desimal.
 */
function getRandomNumber(min, max, decimalPlaces) {
    const rand = Math.random() * (max - min) + min;
    return rand.toFixed(decimalPlaces);
}

/**
 * Mengirim dua payload data berbeda ke topik MQTT secara berkala dengan nilai acak.
 *
 * @param {string} brokerUrl - URL broker MQTT Anda (misalnya, 'mqtt://localhost:1883').
 * @param {string} topic - Topik MQTT untuk memublikasikan data.
 * @param {number} intervalMs - Interval dalam milidetik untuk memublikasikan setiap payload (misalnya, 5000 untuk 5 detik).
 * @param {object} [options] - Opsi klien MQTT opsional.
 * @returns {object} Instansi klien MQTT.
 */
function publishTwoMqttRandomPayloadsPeriodically(brokerUrl, topic, intervalMs, options = {}) {
    const client = mqtt.connect(brokerUrl, options);

    client.on('connect', () => {
        console.log(`Terhubung ke broker MQTT di ${brokerUrl}`);

        let isPayload1 = true; // Flag untuk bergantian antara payload 1 dan 2

        setInterval(() => {
            let payloadToSend;
            let payloadDescription;

            // Buat payload pertama dengan akhiran '1' dan nilai acak
            const payload1 = {
                "DataTRB245": [
                    {"data": getRandomNumber(1.0, 10.0, 2), "server_name": "Flowmeter_1", "name": "Flowmeter1"},
                    {"data": getRandomNumber(0.1, 1.0, 3), "server_name": "Flowmeter_1", "name": "Totalizer1"},
                    {"data": getRandomNumber(5.0, 20.0, 2), "server_name": "Flowmeter_1", "name": "Velocity1"}
                ]
            };

            // Buat payload kedua dengan akhiran '2' dan nilai acak
            const payload2 = {
                "DataTRB245": [
                    {"data": getRandomNumber(1.0, 10.0, 2), "server_name": "Flowmeter_2", "name": "Flowmeter2"},
                    {"data": getRandomNumber(0.1, 1.0, 3), "server_name": "Flowmeter_2", "name": "Totalizer2"},
                    {"data": getRandomNumber(5.0, 20.0, 2), "server_name": "Flowmeter_2", "name": "Velocity2"}
                ]
            };

            // Tentukan payload mana yang akan dikirim berdasarkan flag
            if (isPayload1) {
                payloadToSend = payload1;
                payloadDescription = "Payload 1 (akhiran '1')";
            } else {
                payloadToSend = payload2;
                payloadDescription = "Payload 2 (akhiran '2')";
            }

            const message = JSON.stringify(payloadToSend);

            client.publish(topic, message, (err) => {
                if (err) {
                    console.error(`Gagal memublikasikan ${payloadDescription} ke topik ${topic}:`, err);
                } else {
                    console.log(`[${new Date().toLocaleTimeString('id-ID')}] Berhasil memublikasikan ${payloadDescription} ke topik ${topic}:`);
                    console.log(message);
                }
            });

            // Ganti flag untuk payload berikutnya
            isPayload1 = !isPayload1;

        }, intervalMs);
    });

    client.on('error', (err) => {
        console.error('Kesalahan klien MQTT:', err);
    });

    client.on('offline', () => {
        console.warn('Klien MQTT offline. Mencoba menyambungkan kembali...');
    });

    client.on('reconnect', () => {
        console.log('Klien MQTT mencoba menyambungkan kembali...');
    });

    return client;
}

// --- Contoh Penggunaan ---
const myMqttBroker = 'mqtt://public.grootech.id:1883'; // Ganti dengan alamat broker MQTT Anda
const myMqttTopic = 'demo/flowmeter'; // Ganti dengan topik yang Anda inginkan
// Untuk memublikasikan setiap 2 menit, kita perlu 2 menit * 60 detik/menit * 1000 milidetik/detik
const publishInterval = 2 * 60 * 1000; // 2 menit

// Panggil fungsi untuk mulai memublikasikan dua payload secara berkala
const mqttClient = publishTwoMqttRandomPayloadsPeriodically(myMqttBroker, myMqttTopic, publishInterval, {
    clientId: 'TRB245_DualPayloadPublisher_NodeJS',
    clean: true
});

// Anda bisa menambahkan setTimeout untuk menghentikan penerbitan setelah jangka waktu tertentu
// setTimeout(() => {
//      console.log('Menghentikan publisher MQTT setelah 60 detik.');
//      mqttClient.end();
// }, 60000); // Hentikan setelah 60 detik