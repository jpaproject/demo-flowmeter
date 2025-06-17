@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    /* Kontainer Utama untuk Remote dan Bagian Kanan (Data Card + Log Table) */
    .ac-dashboard {
        display: flex;
        flex-wrap: wrap; /* Allows items to wrap to the next line on smaller screens */
        justify-content: center; /* Center items initially */
        align-items: flex-start; /* Align items to the top */
        gap: 20px;
        padding: 15px;
        margin: 15px;
        max-width: 1000px; /* Adjust max-width as needed */
        box-sizing: border-box;
    }

    /* Container for the right column (Data Card and Log Table) */
    .right-column-container {
        display: flex;
        flex-direction: column; /* Stack data card and log table vertically */
        gap: 20px;
        flex-grow: 1; /* Allow it to grow and take available space */
        min-width: 300px; /* Minimum width for the right column to avoid squishing */
        max-width: 600px; /* Maximum width for the right column to control its size */
    }

    /* CSS ini hanya berlaku untuk elemen dalam .remote-container */
    .remote-container {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(145deg, #f0f0f0, #e0e0e0);
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
        padding: 20px;
        width: 300px; /* Fixed width for the remote */
        max-width: 90%; /* Responsive fallback */
        text-align: center;
        display: flex;
        flex-direction: column;
        gap: 15px;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    /* Styling untuk Data Card */
    .data-card {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(145deg, #e0f2f7, #c1e4f4);
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
        padding: 20px;
        width: 100%; /* Take full width of its parent (.right-column-container) */
        display: flex;
        flex-direction: column;
        gap: 15px;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #333;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .data-card h3 {
        font-size: 1.3em;
        font-weight: 600;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    .data-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }

    .data-item i {
        font-size: 1.8em;
        color: #00bcd4;
    }

    .data-value {
        font-size: 2em;
        font-weight: 700;
        color: #2196F3;
    }

    .data-label {
        font-size: 0.8em;
        color: #555;
        font-weight: 500;
    }

    /* --- Gaya Remote (adjustments) --- */
    .display-area {
        background: linear-gradient(145deg, #2c3e50, #1a252f);
        border-radius: 10px;
        padding: 15px 12px;
        color: #00e676;
        font-family: 'Poppins', sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: inset 0 0 7px rgba(0, 230, 118, 0.3), 0 0 8px rgba(0, 230, 118, 0.1);
        min-height: 90px;
    }

    .temperature-display {
        font-size: 3.2em;
        font-weight: 600;
        letter-spacing: -1px;
        margin-bottom: 2px;
        display: flex;
        align-items: flex-start;
    }

    .temperature-display .unit {
        font-size: 0.3em;
        align-self: flex-start;
        margin-top: 7px;
        opacity: 0.8;
    }

    .info-indicators {
        display: flex;
        gap: 10px;
        font-size: 0.75em;
        font-weight: 300;
        color: rgba(0, 230, 118, 0.8);
    }

    .info-indicators i {
        margin-right: 3px;
        color: rgba(0, 230, 118, 0.6);
    }

    .controls-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .temp-control-group {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-top: 5px;
    }

    .control-button {
        background: linear-gradient(145deg, #ffffff, #f0f0f0);
        color: #333;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9em;
        font-weight: 500;
        transition: all 0.2s ease;
        box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.07), -3px -3px 6px rgba(255, 255, 255, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .control-button i {
        font-size: 0.9em;
        color: #666;
    }

    .control-button:hover {
        background: linear-gradient(145deg, #f0f0f0, #ffffff);
        box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.09), -2px -2px 4px rgba(255, 255, 255, 0.7);
        transform: translateY(-1px);
    }

    .control-button:active {
        box-shadow: inset 1px 1px 2px rgba(0, 0, 0, 0.1), inset -1px -1px 2px rgba(255, 255, 255, 0.7);
        transform: translateY(0);
        background: #e0e0e0;
    }

    .power-button {
        background: linear-gradient(145deg, #ff6b6b, #e63946);
        color: white;
        font-weight: 600;
        font-size: 1em;
        box-shadow: 3px 3px 6px rgba(230, 57, 70, 0.25), -3px -3px 6px rgba(255, 107, 107, 0.5);
    }

    .power-button i {
        color: white;
    }

    .power-button:hover {
        background: linear-gradient(145deg, #e63946, #ff6b6b);
        box-shadow: 2px 2px 4px rgba(230, 57, 70, 0.3), -2px -2px 4px rgba(255, 107, 107, 0.6);
    }

    .power-button:active {
        box-shadow: inset 1px 1px 2px rgba(230, 57, 70, 0.3), inset -1px -1px 2px rgba(255, 107, 107, 0.6);
        background: #e63946;
    }

    .temp-control-group .temp-btn {
        width: 50px;
        height: 50px;
        font-size: 1.3em;
        border-radius: 50%;
        background: linear-gradient(145deg, #e0f7fa, #b2ebf2);
        color: #00bcd4;
        box-shadow: 3px 3px 6px rgba(0, 188, 212, 0.08), -3px -3px 6px rgba(255, 255, 255, 0.5);
    }

    .temp-control-group .temp-btn i {
        color: #00bcd4;
    }

    .temp-control-group .temp-btn:hover {
        background: linear-gradient(145deg, #b2ebf2, #e0f7fa);
        box-shadow: 2px 2px 4px rgba(0, 188, 212, 0.15), -2px -2px 4px rgba(255, 255, 255, 0.7);
    }

    .temp-control-group .temp-btn:active {
        background: #b2ebf2;
        box-shadow: inset 1px 1px 2px rgba(0, 188, 212, 0.15), inset -1px -1px 2px rgba(255, 255, 255, 0.7);
    }

    .label {
        font-size: 0.75em;
        color: #777;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        margin: 0 6px;
    }

    .mode-control-group, .fan-speed-control-group {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        margin-top: 8px;
    }

    .mode-control-group .label, .fan-speed-control-group .label {
        grid-column: 1 / -1;
        text-align: left;
        margin-bottom: 2px;
        margin-top: 0;
    }

    /* Active button state */
    .control-button.active {
        background: linear-gradient(145deg, #8bc34a, #689f38);
        color: white;
        box-shadow: inset 2px 2px 4px rgba(0, 0, 0, 0.15), inset -2px -2px 4px rgba(255, 255, 255, 0.5);
        transform: translateY(0);
    }

    .control-button.active i {
        color: white;
    }

    /* Add a state for when AC is off */
    .remote-container.off .display-area {
        opacity: 0.6;
        box-shadow: none;
    }
    .remote-container.off .controls-grid .control-button:not(.power-button) {
        pointer-events: none;
        opacity: 0.5;
        filter: grayscale(80%);
        box-shadow: none;
    }
    .remote-container.off .controls-grid .control-button:not(.power-button):hover {
        transform: none;
    }

    /* Styling for Log Table Container */
    .log-table-container {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(145deg, #f0f0f0, #e0e0e0);
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
        padding: 20px;
        width: 100%; /* Take full width of its parent (.right-column-container) */
        box-sizing: border-box;
    }

    .log-table-container h3 {
        font-size: 1.3em;
        font-weight: 600;
        margin-bottom: 15px;
        color: #2c3e50;
        text-align: center;
    }

    .log-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 0.9em;
        table-layout: fixed;
    }

    .log-table th, .log-table td {
        padding: 10px 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        word-wrap: break-word;
    }

    .log-table th {
        background-color: #f8f8f8;
        font-weight: 600;
        color: #555;
        text-transform: uppercase;
    }

    .log-table tbody tr:nth-child(even) {
        background-color: #f0f0f0;
    }

    .log-table tbody tr:hover {
        background-color: #e6e6e6;
    }

    /* Responsiveness: Media Queries */
    @media (max-width: 768px) {
        .ac-dashboard {
            flex-direction: column; /* Stack remote and right column vertically */
            align-items: center;
            gap: 20px;
            padding: 10px;
            margin: 10px auto;
            max-width: 95%;
        }

        .remote-container {
            width: 95%;
            max-width: 300px;
        }

        .right-column-container {
            width: 95%; /* Take full width on small screens */
            max-width: 350px; /* Constrain max width for a cleaner look */
            min-width: unset; /* Remove min-width constraint */
        }

        .data-card {
            width: 100%;
            padding: 18px;
            border-radius: 15px;
        }

        .log-table-container {
            width: 100%;
            padding: 15px;
            border-radius: 15px;
        }

        .display-area {
            padding: 12px 10px;
            min-height: 70px;
        }

        .temperature-display {
            font-size: 2.8em;
        }

        .info-indicators {
            font-size: 0.65em;
            gap: 6px;
        }

        .controls-grid {
            gap: 12px;
        }

        .control-button {
            padding: 8px 12px;
            font-size: 0.8em;
            border-radius: 7px;
        }

        .control-button i {
            font-size: 0.8em;
        }

        .power-button {
            font-size: 0.9em;
        }

        .temp-control-group .temp-btn {
            width: 45px;
            height: 45px;
            font-size: 1.2em;
        }

        .label {
            font-size: 0.65em;
        }

        .mode-control-group, .fan-speed-control-group {
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .data-card h3 {
            font-size: 1.2em;
        }

        .data-item i {
            font-size: 1.6em;
        }

        .data-value {
            font-size: 1.8em;
        }
        .data-label {
            font-size: 0.75em;
        }

        .log-table th, .log-table td {
            padding: 8px 5px;
            font-size: 0.8em;
        }
    }
</style>
@endpush

@section('content')
<div class="ac-dashboard">
    <div class="remote-container">
        <div class="display-area">
            <div class="temperature-display">
                <span id="currentTemp">24</span><span class="unit">°C</span>
            </div>
            <div class="info-indicators">
                <span class="mode-indicator" id="currentMode"><i class="fas fa-fan"></i> Cool</span>
                <span class="fan-indicator" id="currentFan"><i class="fas fa-wind"></i> Auto</span>
            </div>
        </div>

        <div class="controls-grid">
            <button class="control-button power-button" id="powerBtn">
                <i class="fas fa-power-off"></i> On/Off
            </button>

            <div class="temp-control-group">
                <button class="control-button temp-btn" id="tempUp"><i class="fas fa-chevron-up"></i></button>
                <span class="label">SET TEMP</span>
                <button class="control-button temp-btn" id="tempDown"><i class="fas fa-chevron-down"></i></button>
            </div>

            <div class="mode-control-group">
                <span class="label">MODE</span>
                <button class="control-button mode-btn active" data-mode="cool"><i class="fas fa-snowflake"></i> Cool</button>
                <button class="control-button mode-btn" data-mode="dry"><i class="fas fa-tint"></i> Dry</button>
                <button class="control-button mode-btn" data-mode="fan"><i class="fas fa-fan"></i> Fan</button>
                <button class="control-button mode-btn" data-mode="heat"><i class="fas fa-fire"></i> Heat</button>
                <button class="control-button mode-btn" data-mode="auto"><i class="fas fa-sync-alt"></i> Auto</button>
            </div>

            <div class="fan-speed-control-group">
                <span class="label">FAN SPEED</span>
                <button class="control-button fan-btn active" data-speed="auto"><i class="fas fa-tachometer-alt"></i> Auto</button>
                <button class="control-button fan-btn" data-speed="low"><i class="fas fa-wind"></i> Low</button>
                <button class="control-button fan-btn" data-speed="medium"><i class="fas fa-wind"></i> Medium</button>
                <button class="control-button fan-btn" data-speed="high"><i class="fas fa-wind"></i> High</button>
            </div>
        </div>
    </div>

    {{-- New container for the right column (Data Card + Log Table) --}}
    <div class="right-column-container">
        <div class="data-card">
            <h3>Realtime Data</h3>
            <div class="data-item">
                <i class="fas fa-thermometer-half"></i>
                <div>
                    <div class="data-value" id="realtimeTemp">28°C</div>
                    <div class="data-label">Temperature</div>
                </div>
            </div>
            <div class="data-item">
                <i class="fas fa-humidity"></i>
                <div>
                    <div class="data-value" id="realtimeHumidity">65%</div>
                    <div class="data-label">Humidity</div>
                </div>
            </div>
            <div class="data-item">
                <i class="fas fa-humidity"></i>
                <div>
                    <div class="data-value" id="realtimeHumidity">65%</div>
                    <div class="data-label">Humidity</div>
                </div>
            </div>
        </div>

        {{-- Log Table Container --}}
        <div class="log-table-container">
            <h3>AC Operation Log</h3>
            <div style="overflow-x: auto;">
                <table class="log-table">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Temperature</th>
                            <th>Humidity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2023-10-01 12:00</td>
                            <td>24°C</td>
                            <td>60%</td>
                        </tr>
                        <tr>
                            <td>2023-10-01 12:05</td>
                            <td>25°C</td>
                            <td>62%</td>
                        </tr>
                        <tr>
                            <td>2023-10-01 12:10</td>
                            <td>26°C</td>
                            <td>65%</td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const powerBtn = document.getElementById('powerBtn');
        const tempUp = document.getElementById('tempUp');
        const tempDown = document.getElementById('tempDown');
        const currentTempDisplay = document.getElementById('currentTemp');
        const modeButtons = document.querySelectorAll('.mode-btn');
        const currentModeDisplay = document.getElementById('currentMode');
        const fanButtons = document.querySelectorAll('.fan-btn');
        const currentFanDisplay = document.getElementById('currentFan');
        const remoteContainer = document.querySelector('.remote-container');
        const logTableBody = document.getElementById('logTableBody');

        let currentTemp = 24;
        let acOn = true;

        // Realtime data elements
        const realtimeTempDisplay = document.getElementById('realtimeTemp');
        const realtimeHumidityDisplay = document.getElementById('realtimeHumidity');


        // --- Remote AC Logic ---
        function updatePowerState() {
            if (acOn) {
                remoteContainer.classList.remove('off');
                currentTempDisplay.textContent = currentTemp;
                currentModeDisplay.style.opacity = 1;
                currentFanDisplay.style.opacity = 1;
                powerBtn.classList.add('active');
            } else {
                remoteContainer.classList.add('off');
                currentTempDisplay.textContent = '--';
                currentModeDisplay.style.opacity = 0.3;
                currentFanDisplay.style.opacity = 0.3;
                powerBtn.classList.remove('active');
            }
        }

        updatePowerState(); // Initial power state setup

        powerBtn.addEventListener('click', () => {
            acOn = !acOn;
            updatePowerState();
        });

        tempUp.addEventListener('click', () => {
            if (acOn && currentTemp < 30) {
                currentTemp++;
                currentTempDisplay.textContent = currentTemp;
            }
        });

        tempDown.addEventListener('click', () => {
            if (acOn && currentTemp > 16) {
                currentTemp--;
                currentTempDisplay.textContent = currentTemp;
            }
        });

        modeButtons.forEach(button => {
            button.addEventListener('click', () => {
                if (acOn) {
                    modeButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    const modeText = button.textContent.trim();
                    const modeIcon = button.querySelector('i') ? button.querySelector('i').outerHTML : '';
                    currentModeDisplay.innerHTML = `${modeIcon} ${modeText}`;
                }
            });
        });

        fanButtons.forEach(button => {
            button.addEventListener('click', () => {
                if (acOn) {
                    fanButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    const fanText = button.textContent.trim();
                    const fanIcon = button.querySelector('i') ? button.querySelector('i').outerHTML : '';
                    currentFanDisplay.innerHTML = `${fanIcon} ${fanText}`;
                }
            });
        });

        // --- Realtime Data Logic ---
        function updateRealtimeData() {
            const temp = (Math.random() * (35 - 20) + 20).toFixed(1);
            const humidity = (Math.random() * (90 - 40) + 40).toFixed(0);

            realtimeTempDisplay.textContent = `${temp}°C`;
            realtimeHumidityDisplay.textContent = `${humidity}%`;
        }

        setInterval(updateRealtimeData, 3000);
        updateRealtimeData();

    });
</script>
@endpush