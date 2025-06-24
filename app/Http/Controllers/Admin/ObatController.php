<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::all();
        return view('admin.obat', compact('obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'kemasan' => 'required|string',
            'harga' => 'required|integer|min:0',
        ]);

        Obat::create($request->all());

        return redirect()->route('admin.obat')->with('success', 'Obat berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->update($request->all());

        return redirect()->route('admin.obat')->with('success', 'Obat berhasil diperbarui');
    }

    public function destroy($id)
    {
        Obat::destroy($id);
        return redirect()->route('admin.obat')->with('success', 'Obat berhasil dihapus');
    }
}
