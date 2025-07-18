<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
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
            'perusahaan_code' => 'required|unique:perusahaan|max:10',
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
        return redirect()->route('adminsystem.perusahaan.index')->with('success', 'Perusahaan berhasil dikirim!');
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
    public function getBagian($code)
    {
        $bagians = Bagian::where('perusahaan_id', $code)->get();
        return response()->json($bagians);
    }
    public function operator_getBagian($code)
    {
        $bagians = Bagian::where('perusahaan_id', $code)->get();
        return response()->json($bagians);
    }




    public function operator_index()
    {
        $perusahaans = Perusahaan::all();

        // Mengirim data ke view
        return view('operator.master.perusahaan.index', compact('perusahaans'));
    }
    public function operator_create()
    {
        $pers = Perusahaan::all();

        // Mengirim data ke view
        return view('operator.master.perusahaan.create', compact('pers'));
    }
    public function operator_edit($perusahaan_code)
    {
        $perusahaan = Perusahaan::findOrFail($perusahaan_code);

        // Mengirim data ke view
        return view('operator.master.perusahaan.edit', compact('perusahaan'));
    }
    // Menambahkan perusahaan baru
    public function operator_store(Request $request)
    {
        $request->validate([
            'perusahaan_code' => 'required|unique:perusahaan|max:10',
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

        return redirect()->route('operator.perusahaan.index')->with('success', 'Perusahaan berhasil ditambahkan!');
    }
    public function operator_update(Request $request, $id)
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

        return redirect()->route('operator.perusahaan.index')->with('success', 'Perusahaan berhasil diupdate!');
    }


    // Menghapus perusahaan
    public function operator_destroy($id)
    {
        $perusahaan = Perusahaan::find($id);
        if (!$perusahaan) {
            return response()->json(['message' => 'Perusahaan tidak ditemukan'], 404);
        }

        $perusahaan->delete();
        return redirect()->route('operator.perusahaan.index')->with('success', 'Perusahaan berhasil dikirim!');
    }
    public function operator_Perusahaanshow($id)
    {
        $perusahaan = Perusahaan::find($id);
        if ($perusahaan) {
            return response()->json($perusahaan);
        } else {
            return response()->json(data: ['message' => 'Perusahaan tidak ditemukan']);
        }
    }
}
