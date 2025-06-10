<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoryLog;
use Illuminate\Support\Facades\Log;

class HistoryLogController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validasi data yang masuk dari request
            // Sesuaikan aturan validasi ini agar sesuai dengan kebutuhan bisnis Anda
            $validatedData = $request->validate([
                'flowmeter' => 'nullable|numeric',
                'totalizer' => 'nullable|numeric',
                'velocity' => 'nullable|numeric',
                'key' => 'nullable|string|max:255', // Pastikan 'key' adalah string
            ]);

            // Buat entri baru di tabel history_logs
            $historyLog = HistoryLog::create($validatedData);

            // Log keberhasilan penyimpanan (opsional, untuk debugging)
            Log::info('History log successfully stored:', $historyLog->toArray());

            // Berikan respons sukses
            return response()->json([
                'message' => 'History log created successfully',
                'data' => $historyLog
            ], 201); // 201 Created

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani error validasi
            Log::error('Validation error for history log:', ['errors' => $e->errors(), 'request_data' => $request->all()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity

        } catch (\Exception $e) {
            // Tangani error umum lainnya
            Log::error('Error storing history log:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'request_data' => $request->all()]);
            return response()->json([
                'message' => 'Failed to store history log',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }
}
