@extends('layouts.main')

@section('content')
<div class="container bg-light min-vh-100">
    <div class="row mb-4">
        <!-- Profile Card -->
        <div class="col-md-6 mb-2">
            <div class="card shadow-sm" style="border-radius: 20px;">
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->name) }}&background=random&size=128"
                         class="rounded-circle mb-3 shadow" alt="Profile Picture" width="120" height="120">

                    <h3 class="card-title text-primary">{{ $employee->name }}</h3>

                    <ul class="list-group list-group-flush text-start mt-3">
                        <li class="list-group-item"><strong>NIP:</strong> {{ $employee->nip }}</li>
                        <li class="list-group-item"><strong>Posisi:</strong> {{ $employee->position }}</li>
                        <li class="list-group-item"><strong>Departemen:</strong> {{ $employee->department }}</li>
                        <li class="list-group-item"><strong>Company:</strong> {{ $employee->company }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Absensi Card -->
        <div class="col-md-6 mb-2">
            <div class="card shadow-sm" style="border-radius: 20px;">
                <div class="card-body text-center">
                    <h2 class="card-title mb-4 text-success">Absensi Kerja</h2>

                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="" method="POST" class="d-grid gap-2 mb-3">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg">
                            🕘 Absen Masuk
                        </button>
                    </form>

                    <form action="" method="POST" class="d-grid gap-2">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-lg">
                            🕔 Selesai Kerja
                        </button>
                    </form>

                    <div class="mt-4 text-muted small">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- History Absensi -->
    <div class="card shadow-sm" style="border-radius: 20px;">
        <div class="card-body">
            <h4 class="card-title mb-3 text-center">Riwayat Absensi</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $dummyAttendances = [
                                ['date' => '2025-04-28', 'clock_in' => '08:03', 'clock_out' => '17:01'],
                                ['date' => '2025-04-27', 'clock_in' => '08:05', 'clock_out' => '17:10'],
                                ['date' => '2025-04-26', 'clock_in' => '08:02', 'clock_out' => null],
                                ['date' => '2025-04-25', 'clock_in' => null, 'clock_out' => null],
                                ['date' => '2025-04-24', 'clock_in' => '07:59', 'clock_out' => '17:03'],
                            ];
                        @endphp

                        @foreach ($dummyAttendances as $attendance)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($attendance['date'])->format('d-m-Y') }}</td>
                                <td>{{ $attendance['clock_in'] ?? '-' }}</td>
                                <td>{{ $attendance['clock_out'] ?? '-' }}</td>
                                <td>
                                    @if ($attendance['clock_in'] && $attendance['clock_out'])
                                        <span class="badge bg-success">Lengkap</span>
                                    @elseif ($attendance['clock_in'])
                                        <span class="badge bg-warning text-dark">Belum Keluar</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Hadir</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
