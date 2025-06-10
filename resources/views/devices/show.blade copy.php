@extends('layouts.main')
@push('css')
    <style>
        .realtime-value {
            font-size: 3.5em;
            font-weight: bold;
            /* color: #0d6efd; */
            color: black;
        }
    </style>
@endpush
@section('content')
<h1 class="mb-5 text-primary">Detail Flowmeter: {{ $device->display_name }}</h1>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow rounded">
            <div class="card-body text-center py-4">
                <h5 class="card-title text-muted mb-3">Realtime Flowmeter</h5>
                {{-- ID spesifik untuk Flowmeter perangkat ini --}}
                <p class="realtime-value mb-1" id="realtime_flowmeter_{{ $device->name }}">
                    Menunggu Data... <span class="fs-4">L/min</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow rounded">
            <div class="card-body text-center py-4">
                <h5 class="card-title text-muted mb-3">Realtime Totalizer</h5>
                {{-- ID spesifik untuk Totalizer perangkat ini --}}
                <p class="realtime-value mb-1" id="realtime_totalizer_{{ $device->name }}">
                    Menunggu Data... <span class="fs-4">m3</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow rounded">
            <div class="card-body text-center py-4">
                <h5 class="card-title text-muted mb-3">Realtime Velocity</h5>
                {{-- ID spesifik untuk Velocity perangkat ini --}}
                <p class="realtime-value mb-1" id="realtime_velocity_{{ $device->name }}">
                    Menunggu Data... <span class="fs-4">m/s</span>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-body py-4">
                <div class="table-responsive">
                    <table class="table datatable" id="datatable_1">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Timestamp</th>
                                <th>Flowrate (L/min)</th>
                                <th>Totalizer (m3)</th>
                                <th>Velocity (m/s)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $log->flowmeter }} L/min</td>
                                    <td>{{ $log->totalizer }} m3</td>
                                    <td>{{ $log->velocity }} m/s</td> {{-- Assuming 'temperature' column holds Velocity --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dapatkan nama perangkat saat ini dari Blade, ini akan menjadi 'key' untuk filtering
        const currentDeviceName = "{{ $device->name }}";
        console.log('Current Device Name for this page:', currentDeviceName);

        // Inisialisasi koneksi Socket.IO
        const socket = io({!! json_encode(env('WEBSOCKET_URL', 'http://localhost:3030')) !!}, {
            transports: ['websocket'],
            reconnection: true,
            reconnectionAttempts: 5,
        });

        socket.on('connect', () => {
            console.log('Connected to WebSocket server');
        });

        // Event listener untuk menerima data filtered real-time dari Node.js
        socket.on('realtime_data', (msg) => {
            console.log('Received filtered real-time data:', msg);

            const deviceKey = msg.key;
            const realtimeData = msg.data;

            // Pastikan data yang diterima adalah untuk perangkat yang sedang dilihat di halaman ini
            if (deviceKey === currentDeviceName && realtimeData && realtimeData.length > 0) {
                console.log(`Updating data for device: ${deviceKey}`);
                realtimeData.forEach(item => {
                    if (item.name.includes('Flowmeter')) {
                        const flowmeterElement = document.getElementById(`realtime_flowmeter_${deviceKey}`);
                        if (flowmeterElement) {
                            flowmeterElement.innerHTML = `${parseFloat(item.data).toFixed(2)} <span class="fs-4">L/min</span>`;
                        }
                    } else if (item.name.includes('Totalizer')) {
                        const totalizerElement = document.getElementById(`realtime_totalizer_${deviceKey}`);
                        if (totalizerElement) {
                            totalizerElement.innerHTML = `${parseFloat(item.data).toLocaleString()} <span class="fs-4">m3</span>`;
                        }
                    } else if (item.name.includes('Velocity')) {
                        // Memetakan Velocity dari MQTT ke Velocity di UI
                        const velocityElement = document.getElementById(`realtime_velocity_${deviceKey}`);
                        if (velocityElement) {
                            velocityElement.innerHTML = `${parseFloat(item.data).toFixed(2)} <span class="fs-4">m/s</span>`;
                        }
                    }
                });
            } else {
                console.log('Received data for a different device or invalid data:', deviceKey, realtimeData);
            }
        });

        socket.on('connect_error', (error) => {
            console.error('WebSocket connection error:', error);
        });

        socket.on('disconnect', (reason) => {
            console.log('Disconnected from WebSocket server:', reason);
        });
    });
</script>
@endpush
