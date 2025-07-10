<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoryLog; // Assuming you have a Report model
use App\Models\TotalizerPrice; // Assuming you have a model for totalizer prices
use Carbon\Carbon; // Make sure to import Carbon for date manipulation
use Barryvdh\DomPDF\Facade\Pdf;


class ReportController extends Controller
{
    public function reportTotalizer(Request $request)
    {
        $totalizerPrice = TotalizerPrice::first();
        $periode = $request->input('periode', 'harian');

        // =============================
        // PERIODE BULANAN
        // =============================
        if ($periode === 'bulanan') {
            $selectedMonth = $request->input('month', now()->format('Y-m'));

            $startOfMonth = Carbon::parse($selectedMonth)->startOfMonth();
            $endOfMonth = Carbon::parse($selectedMonth)->endOfMonth();

            $logs = [];
            $total = 0;

            for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
                $currentDate = $date->copy();

                // Ambil data pertama & terakhir berdasarkan created_at
                $startLog = HistoryLog::whereBetween('created_at', [
                        $currentDate->copy()->startOfDay(),
                        $currentDate->copy()->endOfDay()
                    ])
                    ->orderBy('created_at', 'asc')
                    ->first();

                $endLog = HistoryLog::whereBetween('created_at', [
                        $currentDate->copy()->startOfDay(),
                        $currentDate->copy()->endOfDay()
                    ])
                    ->orderBy('created_at', 'desc')
                    ->first();

                $startVal = $startLog ? (float) $startLog->totalizer : null;
                $endVal = $endLog ? (float) $endLog->totalizer : null;

                $konsumsi = ($startVal !== null && $endVal !== null) ? round($endVal - $startVal, 3) : null;
                if ($konsumsi !== null) {
                    $total += $konsumsi;
                }

                $logs[] = [
                    'tanggal' => $currentDate->format('d-m-Y'),
                    'start' => $startVal !== null ? number_format($startVal, 3) : '-',
                    'end' => $endVal !== null ? number_format($endVal, 3) : '-',
                    'konsumsi' => $konsumsi !== null ? number_format($konsumsi, 3) : '-',
                ];
            }

            return view('reports.index', [
                'logs' => $logs,
                'periode' => 'bulanan',
                'selectedMonth' => $selectedMonth,
                'total' => $total,
                'price' => $totalizerPrice->price,
            ]);
        }

        // =============================
        // PERIODE HARIAN
        // =============================
        $selectedDate = $request->input('date', now()->format('Y-m-d'));

        $logs = [];
        $total = 0;
        $prevEndVal = null;

        for ($hour = 0; $hour < 23; $hour++) {
            $startJam = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
            $endJam = str_pad($hour + 1, 2, '0', STR_PAD_LEFT) . ':00';

            $endStart = Carbon::parse("$selectedDate $endJam")->copy()->subSecond();
            $endEnd = Carbon::parse("$selectedDate $endJam")->copy()->endOfHour();

            $endLog = HistoryLog::whereBetween('created_at', [$endStart, $endEnd])
                ->orderByDesc('created_at')
                ->first();

            $endVal = $endLog ? round($endLog->totalizer, 3) : null;
            $startVal = $prevEndVal;

            $konsumsi = ($startVal !== null && $endVal !== null) ? round($endVal - $startVal, 3) : null;
            if ($konsumsi !== null) {
                $total += $konsumsi;
            }

            $logs[] = [
                'tanggal' => date('d-m-Y', strtotime($selectedDate)),
                'jam' => "$startJam - $endJam",
                'start' => $startVal !== null ? number_format($startVal, 3) : '-',
                'end' => $endVal !== null ? number_format($endVal, 3) : '-',
                'konsumsi' => $konsumsi !== null ? number_format($konsumsi, 3) : '-',
            ];

            $prevEndVal = $endVal;
        }

        return view('reports.index', [
            'logs' => $logs,
            'periode' => 'harian',
            'selectedDate' => $selectedDate,
            'total' => $total,
            'price' => $totalizerPrice->price,
        ]);
    }


    public function reportTotalizerPdf(Request $request)
    {
        $periode = $request->input('periode');
        if ($periode !== 'bulanan') {
            abort(404);
        }

        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $startOfMonth = \Carbon\Carbon::parse($selectedMonth)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::parse($selectedMonth)->endOfMonth();

        $totalizerPrice = \App\Models\TotalizerPrice::first();
        $device = \App\Models\Device::first();

        // Pastikan device & harga tersedia
        if (!$device || !$totalizerPrice) {
            abort(500, 'Device atau Harga Totalizer tidak tersedia.');
        }

        $total = 0;

        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            $tanggal = $date->format('Y-m-d');

            // Jangan ubah objek $date langsung, gunakan clone
            $startLog = \App\Models\HistoryLog::whereBetween('created_at', [
                    $date->copy()->startOfDay(),
                    $date->copy()->endOfDay()
                ])
                ->orderBy('created_at', 'asc')
                ->first();

            $endLog = \App\Models\HistoryLog::whereBetween('created_at', [
                    $date->copy()->startOfDay(),
                    $date->copy()->endOfDay()
                ])
                ->orderBy('created_at', 'desc')
                ->first();

            if ($startLog && $endLog && $startLog->totalizer !== null && $endLog->totalizer !== null) {
                $startVal = (float) $startLog->totalizer;
                $endVal = (float) $endLog->totalizer;

                $selisih = round($endVal - $startVal, 3);
                $total += $selisih;
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.invoice-pdf', [
            'customer_name' => $device->nama_pelanggan,
            'customer_number' => $device->nomor_pelanggan,
            'area' => $device->area ? $device->area->name : 'Tidak Diketahui',
            'selectedMonth' => \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y'),
            'total' => number_format($total, 3),
            'price' => $totalizerPrice->price,
            'tagihan' => number_format($total * $totalizerPrice->price, 0, ',', '.')
        ])->setPaper('A4', 'portrait');

        return $pdf->stream("Invoice-Totalizer-$selectedMonth.pdf");
    }



}
