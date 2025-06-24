<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use Illuminate\Http\Request;

class PoliController extends Controller
{
    public function index()
    {
        $polis = Poli::all();
        return view('admin.poli', compact('polis'));
    }

    public function create()
    {
        return view('admin.poli.store');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_poli' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        Poli::create($request->all());

        return redirect()->route('admin.poli')->with('success', 'Poli berhasil ditambahkan');
    }

    public function show($id)
    {
        $poli = Poli::findOrFail($id);
        return view('admin.poli.show', compact('poli'));
    }

    public function edit($id)
    {
        $poli = Poli::findOrFail($id);
        return view('admin.poli.edit', compact('poli'));
    }

    public function update(Request $request, $id)
    {
        $poli = Poli::findOrFail($id);
        $poli->update($request->all());

        return redirect()->route('admin.poli')->with('success', 'Poli berhasil diperbarui');
    }

    public function destroy($id)
    {
        Poli::destroy($id);
        return redirect()->route('admin.poli')->with('success', 'Poli berhasil dihapus');
    }
}
