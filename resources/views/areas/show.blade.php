@extends('layouts.main')

@section('content')
<div class="row">
    @foreach ($area->devices as $device)
    <div class="col-md-4">
        <div class="card shadow mb-4"> {{-- Added mb-4 for consistent spacing --}}
            <div class="card-body">
                <h5 class="card-title">{{$device->display_name}}</h5>
                {{-- ID elemen disesuaikan untuk update real-time --}}
                <p class="card-text mb-1" id="flowrate_{{$device->name}}">
                    Flowrate: <strong class="text-info">Menunggu Data...</strong>
                </p>
                <p class="card-text mb-1" id="totalizer_{{$device->name}}">
                    Totalizer: <strong class="text-info">Menunggu Data...</strong>
                </p>
                <p class="card-text mb-3" id="velocity_{{$device->name}}">
                    Velocity: <strong class="text-info">Menunggu Data...</strong> {{-- Changed to text-info for velocity --}}
                </p>
                <a href="{{ route('devices.show', $device->id) }}" class="btn btn-primary btn-sm mt-3">
                    <i class="fas fa-info-circle me-1"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('js') {{-- Menggunakan @push('js') --}}
<script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi koneksi Socket.IO
      
        const socket = io({!! json_encode(env('WEBSOCKET_URL', 'http://localhost:3030')) !!}, {
            transports: ['websocket'],
            reconnection: true, // Enable automatic reconnection
            reconnectionAttempts: 5, // Number of reconnection attempts
        });

        // Event listener untuk koneksi berhasil ke WebSocket server
        socket.on('connect', () => {
            console.log('Connected to WebSocket server');
        });

        // Event listener untuk menerima data filtered real-time dari Node.js
        // Pastikan nama event ini cocok dengan yang di-emit dari Node.js ('realtimeFiltered')
        socket.on('realtime_data', (msg) => {
            console.log('Received filtered real-time data:', msg);

            // 'msg.key' akan berisi 'name' dari device (misal: "1", "2")
            // 'msg.data' adalah array dari data MQTT yang difilter untuk device tersebut
            const deviceKey = msg.key;
            const realtimeData = msg.data;

            if (deviceKey && realtimeData && realtimeData.length > 0) {
                // Iterasi setiap item data yang diterima untuk device ini
                realtimeData.forEach(item => {
                    if (item.name.includes('Flowrate')) {
                        const flowrateElement = document.getElementById(`flowrate_${deviceKey}`);
                        if (flowrateElement) {
                            flowrateElement.querySelector('strong').textContent = `${parseFloat(item.data).toFixed(2)} L/min`;
                        }
                    } else if (item.name.includes('Totalizer')) {
                        const totalizerElement = document.getElementById(`totalizer_${deviceKey}`);
                        if (totalizerElement) {
                            totalizerElement.querySelector('strong').textContent = `${parseFloat(item.data).toLocaleString()} m3`;
                        }
                    } else if (item.name.includes('Velocity')) {
                        // Memetakan Velocity dari MQTT ke Velocity di UI
                        const velocityElement = document.getElementById(`velocity_${deviceKey}`);
                        if (velocityElement) {
                            velocityElement.querySelector('strong').textContent = `${parseFloat(item.data).toFixed(2)} m/s`; // Asumsi satuan m/s
                        }
                    }
                });
            }
        });

        // Event listener untuk data perangkat awal (jika Anda ingin menampilkan ini juga)
        socket.on('deviceData', (devices) => {
            console.log('Initial device data received:', devices);
            // Anda bisa menggunakan data ini untuk inisialisasi UI jika diperlukan,
            // atau cukup menunggu data real-timeFiltered
        });

        // Event listener untuk error koneksi
        socket.on('connect_error', (error) => {
            console.error('WebSocket connection error:', error);
        });

        // Event listener untuk disconnect
        socket.on('disconnect', (reason) => {
            console.log('Disconnected from WebSocket server:', reason);
        });
    });
</script>
@endpush
