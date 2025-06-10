@extends('layouts.main') <!-- Hapus baris ini kalau tidak pakai layout -->

@section('content')
<div class="container  min-vh-100 bg-light">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px; border-radius: 20px;">
        <div class="card-body text-center">
            <h2 class="card-title mb-4 text-primary">Absensi Kerja</h2>

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
@endsection
