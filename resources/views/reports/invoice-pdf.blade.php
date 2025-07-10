<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice Totalizer</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            margin: 40px;
        }

        .header {
            background-color: #007bff;
            color: #fff;
            padding: 15px 20px;
            text-align: center;
            border-radius: 5px;
        }

        .info-box {
            border: 1px solid #ccc;
            padding: 15px 20px;
            margin-top: 20px;
            border-radius: 5px;
            width: 100%;
        }

        .info-box table {
            width: 100%;
        }

        .info-box td {
            padding: 6px 10px;
            vertical-align: top;
        }

        .summary-box {
            margin-top: 30px;
            padding: 15px 20px;
            border: 2px solid #007bff;
            border-radius: 5px;
            background-color: #f8f9fa;
            width: 100%;
        }

        .summary-box p {
            margin: 10px 0;
            font-size: 16px;
        }

        .summary-box strong {
            display: inline-block;
            width: 180px;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>INVOICE PENGGUNAAN TOTALIZER</h2>
        <p>Periode Bulanan</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td><strong>Nama Pelanggan</strong></td>
                <td>: {{ $customer_name }}</td>
            </tr>
            <tr>
                <td><strong>Nomor Pelanggan</strong></td>
                <td>: {{ $customer_number }}</td>
            </tr>
            <tr>
                <td><strong>Area</strong></td>
                <td>: {{ $area }}</td>
            </tr>
            <tr>
                <td><strong>Periode</strong></td>
                <td>: {{ $selectedMonth }}</td>
            </tr>
        </table>
    </div>

    <div class="summary-box">
        <p><strong>Total Konsumsi:</strong> {{ $total }} m³</p>
        <p><strong>Harga per m³:</strong> Rp {{ number_format($price, 0, ',', '.') }}</p>
        <p><strong>Total Tagihan:</strong> Rp {{ $tagihan }}</p>
    </div>

    <div class="footer">
        <p>Invoice ini dihasilkan secara otomatis oleh sistem. Tidak perlu tanda tangan.</p>
    </div>

</body>
</html>
