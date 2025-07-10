<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoryLog; // Assuming you have a Report model
use Carbon\Carbon; // Make sure to import Carbon for date manipulation
use PDF; // Assuming you are using a package like barryvdh/laravel-dompdf for PDF generation

class ReportController extends Controller
{
    public function reportTotalizer(Request $request)
    {
        $periode = $request->input('periode', 'harian');

        if ($periode === 'bulanan') {
            $selectedMonth = $request->input('month', now()->format('Y-m'));

            $startOfMonth = Carbon::parse($selectedMonth)->startOfMonth();
            $endOfMonth = Carbon::parse($selectedMonth)->endOfMonth();

            $logs = [];
            $total = 0;

            for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
                $currentDate = $date->copy();

                $log = HistoryLog::whereBetween('created_at', [
                    $currentDate->copy()->startOfDay(),
                    $currentDate->copy()->endOfDay()
                ])
                ->orderBy('created_at')
                ->first();

                $value = $log ? (float) $log->totalizer : 0;
                $total += $value;

                $logs[] = [
                    'tanggal' => $currentDate->format('d-m-Y'),
                    'totalizer' => $log ? number_format($log->totalizer, 3) : '-',
                ];
            }

            return view('reports.index', [
                'logs' => $logs,
                'periode' => 'bulanan',
                'selectedMonth' => $selectedMonth,
                'total' => $total,
            ]);
        }

        // Harian
        $selectedDate = $request->input('date', now()->format('Y-m-d'));

        $logs = [];
        $prevTotalizer = null;
        $total = 0;

        for ($hour = 0; $hour < 24; $hour++) {
            $start = date('Y-m-d H:i:s', strtotime("$selectedDate $hour:00:00"));
            $end = date('Y-m-d H:i:s', strtotime("$selectedDate $hour:59:59"));

            $log = HistoryLog::whereBetween('created_at', [$start, $end])
                ->orderBy('created_at')
                ->first();

            $jam = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';

            if ($log) {
                $totalizer = $log->totalizer;
                $selisih = $prevTotalizer !== null ? round($totalizer - $prevTotalizer, 3) : 0;
                if ($prevTotalizer !== null) {
                    $total += $selisih;
                }
                $prevTotalizer = $totalizer;
            } else {
                $totalizer = null;
                $selisih = null;
            }

            $logs[] = [
                'tanggal' => date('d-m-Y', strtotime($selectedDate)),
                'jam' => $jam,
                'totalizer' => $totalizer !== null ? number_format($totalizer, 3) : '-',
                'selisih' => $selisih !== null ? number_format($selisih, 3) : '-',
            ];
        }

        return view('reports.index', [
            'logs' => $logs,
            'periode' => 'harian',
            'selectedDate' => $selectedDate,
            'total' => $total,
        ]);
    }

    public function reportTotalizerPdf(Request $request)
    {
        $periode = $request->input('periode', 'harian');

        if ($periode === 'bulanan') {
            $selectedMonth = $request->input('month', now()->format('Y-m'));
            $startOfMonth = Carbon::parse($selectedMonth)->startOfMonth();
            $endOfMonth = Carbon::parse($selectedMonth)->endOfMonth();

            $logs = [];

            for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
                $currentDate = $date->copy(); // FIX: Prevent mutation issues

                $log = HistoryLog::whereBetween('created_at', [
                    $currentDate->copy()->startOfDay(),
                    $currentDate->copy()->endOfDay()
                ])
                ->orderBy('created_at')
                ->first();

                $logs[] = [
                    'tanggal' => $currentDate->format('d-m-Y'),
                    'totalizer' => $log ? number_format($log->totalizer, 3) : '-',
                ];
            }

            $pdf = Pdf::loadView('reports.pdf', [
                'periode' => 'bulanan',
                'logs' => $logs,
                'selectedMonth' => $selectedMonth,
            ]);

            return $pdf->download("report-bulanan-$selectedMonth.pdf");
        }

        // Harian
        $selectedDate = $request->input('date', now()->format('Y-m-d'));
        $logs = [];
        $prevTotalizer = null;

        for ($hour = 0; $hour < 24; $hour++) {
            $start = date('Y-m-d H:i:s', strtotime("$selectedDate $hour:00:00"));
            $end = date('Y-m-d H:i:s', strtotime("$selectedDate $hour:59:59"));

            $log = HistoryLog::whereBetween('created_at', [$start, $end])
                ->orderBy('created_at')
                ->first();

            $jam = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';

            if ($log) {
                $totalizer = $log->totalizer;
                $selisih = $prevTotalizer !== null ? round($totalizer - $prevTotalizer, 3) : 0;
                $prevTotalizer = $totalizer;
            } else {
                $totalizer = null;
                $selisih = null;
            }

            $logs[] = [
                'tanggal' => date('d-m-Y', strtotime($selectedDate)),
                'jam' => $jam,
                'totalizer' => $totalizer !== null ? number_format($totalizer, 3) : '-',
                'selisih' => $selisih !== null ? number_format($selisih, 3) : '-',
            ];
        }

        $pdf = Pdf::loadView('reports.pdf', [
            'periode' => 'harian',
            'logs' => $logs,
            'selectedDate' => $selectedDate,
        ]);

        return $pdf->download("report-harian-$selectedDate.pdf");
    }

}
