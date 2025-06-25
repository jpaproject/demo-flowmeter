<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TotalizerPrice;

class TotalizerPriceController extends Controller
{
    public function create()
    {
        return view('prices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        // Asumsikan hanya satu data harga yang disimpan (misalnya id = 1)
        $harga = TotalizerPrice::first(); // Ambil data pertama, atau null

        if ($harga) {
            // Jika sudah ada, update
            $harga->price = $request->price;
            $harga->save();
            return redirect()->back()->with('success', 'Harga berhasil diperbarui.');
        } else {
            // Jika belum ada, buat baru
            TotalizerPrice::create([
                'price' => $request->price
            ]);
            return redirect()->back()->with('success', 'Harga berhasil ditambahkan.');
        }
    }
}
