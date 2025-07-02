<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Totalizer</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>
<body>

    <h3>Laporan Sensor Totalizer - {{ ucfirst($periode) }}</h3>

    @if ($periode === 'harian')
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</p>
    @else
        <p><strong>Bulan:</strong> {{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                @if ($periode === 'harian')
                    <th>Jam</th>
                    <th>Totalizer (m³)</th>
                    <th>Konsumsi</th>
                @else
                    <th>Totalizer (m³)</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $index => $log)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $log['tanggal'] }}</td>

                    @if ($periode === 'harian')
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

</body>
</html>
