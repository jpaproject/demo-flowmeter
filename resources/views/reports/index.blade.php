@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-header">
                <h4>Report Totalizer Usage</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('report.totalizer') }}">
                    <div class="row align-items-end">
                        <div class="col-lg-3 col-md-6 mb-2">
                            <label for="periode">Periode</label>
                            <select name="periode" id="periode" class="form-control">
                                <option value="harian" {{ (request('periode') ?? $periode ?? 'harian') == 'harian' ? 'selected' : '' }}>Harian</option>
                                <option value="bulanan" {{ (request('periode') ?? $periode ?? '') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-2" id="date_column">
                            <label for="periode">Tanggal</label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
                        </div>

                        <div class="col-lg-3 col-md-6 mb-2 d-none" id="month_column">
                            <label for="periode">Bulan</label>
                            <input type="month" name="month" id="month" class="form-control" value="{{ $selectedMonth ?? '' }}">
                        </div>

                        {{-- Cetak PDF --}}
                        <div class="col-lg-3 col-md-6 mb-2">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="print_pdf" id="print_pdf" value="1" {{ request('print_pdf') ? 'checked' : '' }}>
                                <label class="form-check-label" for="print_pdf">
                                    Cetak sebagai PDF
                                </label>
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="col-lg-3 col-md-6 mb-2 d-flex justify-content-between align-items-end">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter menu-icon"></i>
                                    Filter</button>
                                <a href="{{ route('report.totalizer') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo-alt menu-icon"></i>
                                    Reset</a>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Total --}}
                <div class="mt-3 mb-3">
                    <h5>
                        Total {{ $periode == 'harian' ? 'Penggunaan Totalizer' : 'Totalizer Bulanan' }}:
                        <span class="text-primary">{{ number_format($total, 3) }} m³</span>
                    </h5>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table datatable" id="datatable_1">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                @if ($periode == 'harian')
                                    <th>Jam</th>
                                    <th>Totalizer (m3)</th>
                                    <th>Konsumsi</th>
                                @else
                                    <th>Totalizer (m3)</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $index => $log)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $log['tanggal'] }}</td>
                                    @if ($periode == 'harian')
                                        <td>{{ $log['jam'] }}</td>
                                        <td>{{ $log['totalizer'] }}</td>
                                        <td>{{ $log['selisih'] }}</td>
                                    @else
                                        <td>{{ $log['totalizer'] }}</td>
                                    @endif
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
<script>
    function togglePeriodeInputs() {
        var periode = $('#periode').val();
        if (periode === 'harian') {
            $('#date_column').removeClass('d-none');
            $('#month_column').addClass('d-none');
        } else if (periode === 'bulanan') {
            $('#date_column').addClass('d-none');
            $('#month_column').removeClass('d-none');
        }
    }

    $(document).ready(function () {
        togglePeriodeInputs();
        $('#periode').on('change', function () {
            togglePeriodeInputs();
        });
    });
</script>

<script>
    window.onload = function () {
        const urlParams = new URLSearchParams(window.location.search);
        const printPdf = urlParams.get('print_pdf');

        if (
            printPdf === '1' &&
            urlParams.has('periode') &&
            (urlParams.has('date') || urlParams.has('month'))
        ) {
            const periode = urlParams.get('periode');
            const tanggal = urlParams.get('date');
            const bulan = urlParams.get('month');

            let pdfUrl = "{{ route('report.totalizer.pdf') }}?periode=" + periode;

            if (periode === 'harian' && tanggal) {
                pdfUrl += "&date=" + tanggal;
            } else if (periode === 'bulanan' && bulan) {
                pdfUrl += "&month=" + bulan;
            }

            pdfUrl += "&print_pdf=1";
            window.open(pdfUrl, '_blank');
        }
    };
</script>
@endpush
