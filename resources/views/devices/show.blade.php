@extends('layouts.main') {{-- Pastikan layout ini tersedia --}}

@push('css')
    <style>
        .realtime-value {
            font-size: 1.5em;
            font-weight: bold;
            color: black;
        }
        /* Style untuk container chart */
        #trendingChartContainer {
            position: relative;
            height: 400px; /* Sesuaikan tinggi grafik sesuai kebutuhan */
            width: 100%;
        }
    </style>
@endpush

@section('content')
<h1 class="mb-5 text-primary">Detail Flowmeter: {{ $device->display_name }}</h1>

<div class="row mb-4">
    <div class="col-lg-4">
        <div class="col-md-12">
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
        <div class="col-md-12">
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
        <div class="col-md-12">
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
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-body py-4">
                <div id="trendingChartContainer">
                    <canvas id="trendingChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- <div class="row mb-4">
    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-body py-4">
                <div id="trendingChartContainer">
                    <canvas id="trendingChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div> --}}


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
                            {{-- Data log dari controller (sudah difilter untuk hari ini) --}}
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $log->flowmeter }} L/min</td>
                                    <td>{{ $log->totalizer }} m3</td>
                                    <td>{{ $log->velocity }} m/s</td>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- Ini penting untuk sumbu waktu di Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@latest/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dapatkan nama perangkat saat ini dari Blade, ini akan menjadi 'key' untuk filtering WebSocket
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

        // --- Inisialisasi Chart.js dengan Data Historis (Log Hari Ini) ---

        // Ambil data log yang sudah dikirim dari controller (hanya hari ini)
        const initialLogs = {!! json_encode($trendingLogs) !!};

        // Siapkan array kosong untuk menampung data chart
        const initialLabels = []; // Untuk timestamp di sumbu X
        const initialFlowmeterData = [];
        const initialTotalizerData = [];
        const initialVelocityData = [];

        // Isi array dengan data historis dari $logs
        initialLogs.forEach(log => {
            initialLabels.push(new Date(log.created_at)); // Konversi string tanggal ke objek Date
            initialFlowmeterData.push(parseFloat(log.flowmeter));
            initialTotalizerData.push(parseFloat(log.totalizer));
            initialVelocityData.push(parseFloat(log.velocity));
        });

        // Dapatkan konteks 2D dari elemen canvas
        const ctx = document.getElementById('trendingChart').getContext('2d');

        // Buat objek Chart.js
        const trendingChart = new Chart(ctx, {
            type: 'line', // Jenis chart: garis
            data: {
                labels: initialLabels, // Gunakan data historis sebagai label awal
                datasets: [
                    {
                        label: 'Flowmeter (L/min)',
                        data: initialFlowmeterData, // Gunakan data historis
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1, // Membuat garis sedikit melengkung
                        fill: false // Tidak mengisi area di bawah garis
                    },
                    {
                        label: 'Totalizer (m3)',
                        data: initialTotalizerData, // Gunakan data historis
                        borderColor: 'rgb(255, 99, 132)',
                        tension: 0.1,
                        fill: false
                    },
                    {
                        label: 'Velocity (m/s)',
                        data: initialVelocityData, // Gunakan data historis
                        borderColor: 'rgb(54, 162, 235)',
                        tension: 0.1,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true, // Chart akan menyesuaikan ukuran container
                maintainAspectRatio: false, // Memungkinkan Anda mengatur tinggi secara manual
                scales: {
                    x: {
                        type: 'time', // Sumbu X adalah waktu
                        time: {
                            unit: 'second', // Unit terkecil yang ditampilkan di sumbu X
                            tooltipFormat: 'yyyy-MM-dd HH:mm:ss', // Format tooltip
                            displayFormats: { // Format tampilan label di sumbu X
                                second: 'HH:mm:ss',
                                minute: 'HH:mm',
                                hour: 'MMM D, HH:mm'
                            }
                        },
                        title: {
                            display: true,
                            text: 'Time'
                        }
                    },
                    y: {
                        beginAtZero: true, // Sumbu Y mulai dari 0
                        title: {
                            display: true,
                            text: 'Value'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        mode: 'index', // Tooltip menampilkan semua dataset pada titik yang sama
                        intersect: false // Tooltip muncul bahkan jika kursor tidak tepat di titik data
                    },
                    legend: {
                        display: true, // Menampilkan legenda
                        position: 'top' // Posisi legenda
                    }
                }
            }
        });

        // Fungsi untuk menambahkan data baru ke chart (dari WebSocket)
        function addDataToChart(timestamp, flowmeter, totalizer, velocity) {
            // Batasi jumlah data poin untuk menjaga performa chart
            const maxDataPoints = 120; // Contoh: menyimpan 2 menit data jika update setiap detik

            trendingChart.data.labels.push(timestamp);
            trendingChart.data.datasets[0].data.push(flowmeter);
            trendingChart.data.datasets[1].data.push(totalizer);
            trendingChart.data.datasets[2].data.push(velocity);

            // Jika jumlah data melebihi batas, hapus data tertua
            if (trendingChart.data.labels.length > maxDataPoints) {
                trendingChart.data.labels.shift();
                trendingChart.data.datasets[0].data.shift();
                trendingChart.data.datasets[1].data.shift();
                trendingChart.data.datasets[2].data.shift();
            }
            trendingChart.update(); // Perbarui chart
        }

        // --- WebSocket Listener untuk Data Real-time ---
        socket.on('realtime_data', (msg) => {
            console.log('Received filtered real-time data:', msg);

            const deviceKey = msg.key;
            const realtimeData = msg.data;

            // Pastikan data yang diterima adalah untuk perangkat yang sedang dilihat
            if (deviceKey === currentDeviceName && realtimeData && realtimeData.length > 0) {
                console.log(`Updating data for device: ${deviceKey}`);
                let currentFlowmeter = null;
                let currentTotalizer = null;
                let currentVelocity = null;

                realtimeData.forEach(item => {
                    // Update nilai real-time di UI
                    if (item.name.includes('Flowmeter')) {
                        const flowmeterElement = document.getElementById(`realtime_flowmeter_${deviceKey}`);
                        if (flowmeterElement) {
                            currentFlowmeter = parseFloat(item.data).toFixed(2);
                            flowmeterElement.innerHTML = `${currentFlowmeter} <span class="fs-4">L/min</span>`;
                        }
                    } else if (item.name.includes('Totalizer')) {
                        const totalizerElement = document.getElementById(`realtime_totalizer_${deviceKey}`);
                        if (totalizerElement) {
                            currentTotalizer = parseFloat(item.data).toLocaleString();
                            totalizerElement.innerHTML = `${currentTotalizer} <span class="fs-4">m3</span>`;
                        }
                    } else if (item.name.includes('Velocity')) {
                        const velocityElement = document.getElementById(`realtime_velocity_${deviceKey}`);
                        if (velocityElement) {
                            currentVelocity = parseFloat(item.data).toFixed(2);
                            velocityElement.innerHTML = `${currentVelocity} <span class="fs-4">m/s</span>`;
                        }
                    }
                });

                // Tambahkan data ke chart hanya jika semua nilai real-time tersedia
                if (currentFlowmeter !== null && currentTotalizer !== null && currentVelocity !== null) {
                    addDataToChart(new Date(), parseFloat(currentFlowmeter), parseFloat(currentTotalizer.replace(/,/g, '')), parseFloat(currentVelocity));
                }

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