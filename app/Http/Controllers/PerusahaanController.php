<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
    public function index()
    {
        $perusahaans = Perusahaan::all();

        // Mengirim data ke view
        return view('adminsystem.master.perusahaan.index', compact('perusahaans'));
    }
    public function create()
    {
        $pers = Perusahaan::all();

        // Mengirim data ke view
        return view('adminsystem.master.perusahaan.create', compact('pers'));
    }
    public function edit($perusahaan_code)
    {
        $perusahaan = Perusahaan::findOrFail($perusahaan_code);

        // Mengirim data ke view
        return view('adminsystem.master.perusahaan.edit', compact('perusahaan'));
    }
    // Menambahkan perusahaan baru
    public function store(Request $request)
    {
        $request->validate([
            'perusahaan_code' => 'required|unique:perusahaan',
            'perusahaan_name' => 'required',
            'city' => 'required',
            'street' => 'required',
        ]);

        Perusahaan::create([
            'perusahaan_code' => $request->perusahaan_code,
            'perusahaan_name' => $request->perusahaan_name,
            'city' => $request->city,
            'street' => $request->street,
        ]);

        return redirect()->route('adminsystem.perusahaan.index')->with('success', 'Perusahaan berhasil ditambahkan!');
    }


    public function update(Request $request, $id)
    {
        $perusahaan = Perusahaan::findOrFail($id);

        $request->validate([
            'perusahaan_code' => 'required',
            'perusahaan_name' => 'required',
            'city' => 'required',
            'street' => 'required',
        ]);

        $perusahaan->update([
            'perusahaan_code' => $request->perusahaan_code,
            'perusahaan_name' => $request->perusahaan_name,
            'city' => $request->city,
            'street' => $request->street,
        ]);

        return redirect()->route('adminsystem.perusahaan.index')->with('success', 'Perusahaan berhasil diupdate!');
    }


    // Menghapus perusahaan
    public function destroy($id)
    {
        $perusahaan = Perusahaan::find($id);
        if (!$perusahaan) {
            return response()->json(['message' => 'Perusahaan tidak ditemukan'], 404);
        }

        $perusahaan->delete();
        return redirect()->route('adminsystem.perusahaan.index')->with('notification', 'Perusahaan berhasil dikirim!');
    }
    public function Perusahaanshow($id)
    {
        $perusahaan = Perusahaan::find($id);
        if ($perusahaan) {
            return response()->json($perusahaan);
        } else {
            return response()->json(data: ['message' => 'Perusahaan tidak ditemukan']);
        }
    }
    
}
