<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoryLog;
use Illuminate\Support\Facades\Log;

class HistoryLogController extends Controller
{

    public function index()
    {   
        // Mengambil semua entri history log
        $historyLogs = HistoryLog::orderBy('created_at', 'desc')->get();

        // Mengembalikan view dengan data history logs
        return view('history-logs.index', compact('historyLogs'));
    }

    public function create()
    {
        // Mengembalikan view untuk membuat history log baru
        return view('history-logs.create');
    }

    public function edit($id)
    {
        // Mengambil entri history log berdasarkan ID
        $historyLog = HistoryLog::findOrFail($id);

        // Mengembalikan view untuk mengedit history log
        return view('history-logs.edit', compact('historyLog'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi data dari request
            $validatedData = $request->validate([
                'totalizer' => 'nullable',
                'key' => 'nullable|string|max:255',
                'tanggal' => 'required',
                'jam' => 'required|date_format:H:i',
            ]);

            // Tambahkan waktu manual
            $validatedData['created_at'] = date('Y-m-d H:i:s', strtotime($validatedData['tanggal'] . ' ' . $validatedData['jam']));
            $validatedData['updated_at'] = now();

            // Update entri history log
            $historyLog = HistoryLog::findOrFail($id);
            $historyLog->update($validatedData);

            Log::info('History log successfully updated:', $historyLog->toArray());

            return redirect()->route('history-logs.index')->with('success', 'History log updated successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error for history log:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Error updating history log:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Failed to update history log: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Mengambil entri history log berdasarkan ID
            $historyLog = HistoryLog::findOrFail($id);

            // Hapus entri history log
            $historyLog->delete();

            Log::info('History log successfully deleted:', ['id' => $id]);

            return redirect()->route('history-logs.index')->with('success', 'History log deleted successfully');

        } catch (\Exception $e) {
            Log::error('Error deleting history log:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $id
            ]);
            return redirect()->back()->with('error', 'Failed to delete history log: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi data dari request
            $validatedData = $request->validate([
                'totalizer' => 'nullable',
                'key' => 'nullable|string|max:255',
                'tanggal' => 'required',
                'jam' => 'required|date_format:H:i',
            ]);

            // Tambahkan waktu manual
            $validatedData['created_at'] = date('Y-m-d H:i:s', strtotime($validatedData['tanggal'] . ' ' . $validatedData['jam']));
            $validatedData['updated_at'] = now();

            // Simpan ke database
            $historyLog = HistoryLog::create($validatedData);

            Log::info('History log successfully stored:', $historyLog->toArray());

            return redirect()->route('history-logs.index')->with('success', 'History log created successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error for history log:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Error storing history log:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Failed to store history log: ' . $e->getMessage());
        }
    }

    public function storeApi(Request $request)
    {
        try {
            // Validasi data dari request
            $validatedData = $request->validate([
                'flowmeter' => 'nullable|numeric',
                'totalizer' => 'nullable|numeric',
                'velocity' => 'nullable|numeric',
                'key' => 'nullable|string|max:255',
            ]);

            // Tambahkan waktu manual
            $validatedData['created_at'] = now();
            $validatedData['updated_at'] = now();

            // Simpan ke database
            $historyLog = HistoryLog::create($validatedData);

            Log::info('History log successfully stored:', $historyLog->toArray());

            return response()->json([
                'message' => 'History log created successfully',
                'data' => $historyLog
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error for history log:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error storing history log:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'message' => 'Failed to store history log',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
