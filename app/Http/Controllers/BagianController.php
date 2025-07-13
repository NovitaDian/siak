<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\Perusahaan;
use Illuminate\Http\Request;

class BagianController extends Controller
{
    public function index()
    {
        $bagians = Bagian::all();

        // Mengirim data ke tampilan
        return view('adminsystem.master.bagian.index', compact('bagians'));
    }

    public function store(Request $request)
    {
        // Validasi data yang masuk
        $validated = $request->validate([
            'perusahaan_id' => 'nullable|exists:perusahaan,id',
            'nama_bagian' => 'required|string|max:255',
        ]);

        // Ambil nama perusahaan berdasarkan perusahaan_id
        $perusahaan = Perusahaan::where('id', $request->perusahaan_id)->first();

        if ($perusahaan) {
            // Buat data Bagian baru dengan nama perusahaan yang dipilih
            Bagian::create([
                'perusahaan_id' => $request->perusahaan_id,
                'nama_bagian' => $request->nama_bagian,
            ]);

            // Arahkan kembali dengan pesan sukses
            return redirect()->route('adminsystem.bagian.index')->with('success', 'Bagian berhasil ditambahkan.');
        }

        // Jika gagal, arahkan kembali dengan pesan error
        return redirect()->back()->with('error', 'Perusahaan tidak ditemukan.');
    }

    public function edit($id)
    {
        $bagian = Bagian::findOrFail($id);
        $perusahaans = Perusahaan::all();

        // Mengirim data ke tampilan
        return view('adminsystem.master.bagian.edit', compact('perusahaans', 'bagian'));
    }

    public function create()
    {
        $perusahaans = Perusahaan::all();

        // Mengirim data ke tampilan
        return view('adminsystem.master.bagian.create', compact('perusahaans'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang masuk
        $request->validate([
            'perusahaan_id' => 'nullable|exists:perusahaan,id',
            'nama_bagian' => 'required|string|max:255',
        ]);

        $bagian = Bagian::findOrFail($id);

        // Ambil nama perusahaan berdasarkan perusahaan_id yang dipilih
        $perusahaan = Perusahaan::where('id', $request->perusahaan_id)->first();

        // Perbarui data Bagian
        $bagian->perusahaan_id = $request->perusahaan_id;
        $bagian->nama_bagian = $request->nama_bagian;
        $bagian->save(); // Simpan perubahan

        // Arahkan kembali dengan pesan sukses
        return redirect()->route('adminsystem.bagian.index')->with('success', 'Data Bagian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $bagian = Bagian::find($id);
        if (!$bagian) {
            return response()->json(['message' => 'Bagian tidak ditemukan'], 404);
        }

        $bagian->delete();
        return redirect()->route('adminsystem.bagian.index')->with('success', 'Bagian berhasil dihapus!');
    }

    //  OPERATOR 

    public function operator_index()
    {
        $bagians = Bagian::all();

        // Mengirim data ke tampilan
        return view('operator.master.bagian.index', compact('bagians'));
    }

    public function operator_store(Request $request)
    {
        // Validasi data yang masuk
        $validated = $request->validate([
            'perusahaan_id' => 'nullable|exists:perusahaan,id',
            'nama_bagian' => 'required|string|max:255',
        ]);

        // Ambil nama perusahaan berdasarkan perusahaan_id
        $perusahaan = Perusahaan::where('id', $request->perusahaan_id)->first();

        if ($perusahaan) {
            // Buat data Bagian baru dengan nama perusahaan yang dipilih
            Bagian::create([
                'perusahaan_id' => $request->perusahaan_id,
                'nama_bagian' => $request->nama_bagian,
            ]);

            // Arahkan kembali dengan pesan sukses
            return redirect()->route('operator.bagian.index')->with('success', 'Bagian berhasil ditambahkan.');
        }

        // Jika gagal, arahkan kembali dengan pesan error
        return redirect()->back()->with('error', 'Perusahaan tidak ditemukan.');
    }

    public function operator_edit($id)
    {
        $bagian = Bagian::findOrFail($id);
        $perusahaans = Perusahaan::all();

        // Mengirim data ke tampilan
        return view('operator.master.bagian.edit', compact('perusahaans', 'bagian'));
    }

    public function operator_create()
    {
        $perusahaans = Perusahaan::all();

        // Mengirim data ke tampilan
        return view('operator.master.bagian.create', compact('perusahaans'));
    }

    public function operator_update(Request $request, $id)
    {
        // Validasi data yang masuk
        $request->validate([
            'perusahaan_id' => 'nullable|exists:perusahaan,id',
            'nama_bagian' => 'required|string|max:255',
        ]);

        $bagian = Bagian::findOrFail($id);

        // Ambil nama perusahaan berdasarkan perusahaan_id yang dipilih
        $perusahaan = Perusahaan::where('id', $request->perusahaan_id)->first();

        // Perbarui data Bagian
        $bagian->perusahaan_id = $request->perusahaan_id;
        $bagian->nama_bagian = $request->nama_bagian;
        $bagian->save(); // Simpan perubahan

        // Arahkan kembali dengan pesan sukses
        return redirect()->route('operator.bagian.index')->with('success', 'Data Bagian berhasil diperbarui!');
    }

    public function operator_destroy($id)
    {
        $bagian = Bagian::find($id);
        if (!$bagian) {
            return response()->json(['message' => 'Bagian tidak ditemukan'], 404);
        }

        $bagian->delete();
        return redirect()->route('operator.bagian.index')->with('success', 'Bagian berhasil dihapus!');
    }
}
