<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\NamaAlat;
use App\Models\SentToolReport;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alats = Alat::all();
        $nama_alats = NamaAlat::all();

        // Mengirim data ke view
        return view('adminsystem.master.alat.index', compact('alats', 'nama_alats'));
    }

    // DETAIL ALAT (table alats)


    public function alat_create()
    {
        $nama_alats = NamaAlat::all();
        return view('adminsystem.master.alat.alat.create', compact('nama_alats'));
    }

    public function alat_store(Request $request)
    {
        $request->validate([
            'nama_alat_id' => 'required|exists:nama_alats,id',
            'nomor' => 'required|string|max:255',
            'waktu_inspeksi' => 'nullable|date',
            'durasi_inspeksi' => 'required|integer|min:0',
            'status' => 'nullable|string|max:255'
        ]);

        $namaAlat = NamaAlat::findOrFail($request->nama_alat_id);

        Alat::create([
            'nama_alat_id' => $namaAlat->id,
            'nama_alat' => $namaAlat->nama_alat,
            'nomor' => $request->nomor,
            'waktu_inspeksi' => $request->waktu_inspeksi,
            'durasi_inspeksi' => $request->durasi_inspeksi,
            'status' => $request->status,
        ]);


        return redirect()->route('adminsystem.alat.index')->with('success', 'Alat berhasil ditambahkan.');
    }

    public function alat_edit($id)
    {
        $alat = Alat::findOrFail($id);
        $nama_alats = NamaAlat::all();
        return view('adminsystem.master.alat.alat.edit', compact('alat', 'nama_alats'));
    }

    public function alat_update(Request $request, $id)
    {
        $request->validate([
            'nama_alat' => 'required|exists:nama_alats,nama_alat',
            'nomor' => 'required|string|max:255',
            'waktu_inspeksi' => 'nullable|date',
            'durasi_inspeksi' => 'required|integer|min:0',
            'status' => 'required|string|max:255'
        ]);

        $alat = Alat::findOrFail($id);
        $namaAlat = NamaAlat::findOrFail($request->nama_alat_id);

        $alat->update([
            'nama_alat_id' => $namaAlat->id,
            'nama_alat' => $namaAlat->nama_alat,
            'nomor' => $request->nomor,
            'waktu_inspeksi' => $request->waktu_inspeksi,
            'durasi_inspeksi' => $request->durasi_inspeksi,
            'status' => $request->status,
        ]);

        return redirect()->route('adminsystem.alat.index')->with('success', 'Alat berhasil diperbarui.');
    }

    public function alat_show($id)
    {
        $tool_fixs = SentToolReport::where('alat_id', $id)
            ->orderByDesc('tanggal_pemeriksaan')
            ->get();
        return view('adminsystem.master.alat.alat.show', compact('tool_fixs'));
    }

    public function alat_destroy($id)
    {
        $alat = Alat::findOrFail($id);
        $alat->delete();

        return redirect()->route('adminsystem.alat.index')->with('success', 'Alat berhasil dihapus.');
    }


    // NAMA ALAT MASTER



    public function nama_alat_create()
    {
        return view('adminsystem.master.alat.nama_alat.create');
    }

    public function nama_alat_store(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required|string|unique:nama_alats,nama_alat|max:255',
        ]);

        NamaAlat::create($request->only('nama_alat'));

        return redirect()->route('adminsystem.alat.index')->with('success', 'Nama alat berhasil ditambahkan.');
    }

    public function nama_alat_edit($id)
    {
        $nama_alat = NamaAlat::findOrFail($id);
        return view('adminsystem.master.alat.nama_alat.edit', compact('nama_alat'));
    }

    public function nama_alat_update(Request $request, $id)
    {
        $request->validate([
            'nama_alat' => 'required|string|unique:nama_alats,nama_alat|max:255',
        ]);

        $nama_alat = NamaAlat::findOrFail($id);
        $nama_alat->update($request->only('nama_alat'));

        return redirect()->route('adminsystem.alat.index')->with('success', 'Nama alat berhasil diperbarui.');
    }

    public function nama_alat_show($id)
    {
        $nama_alat = NamaAlat::findOrFail($id);
        return view('adminsystem.master.alat.nama_alat.show', compact('nama_alat'));
    }

    public function nama_alat_destroy($id)
    {
        $nama_alat = NamaAlat::findOrFail($id);
        $nama_alat->delete();

        return redirect()->route('adminsystem.alat.index')->with('success', 'Nama alat berhasil dihapus.');
    }






    public function operator_index()
    {
        $alats = Alat::all();
        $nama_alats = NamaAlat::all();

        // Mengirim data ke view
        return view('operator.master.alat.index', compact('alats', 'nama_alats'));
    }

    // DETAIL ALAT (table alats)


    public function operator_alat_create()
    {
        $nama_alats = NamaAlat::all();
        return view('operator.master.alat.alat.create', compact('nama_alats'));
    }

    public function operator_alat_store(Request $request)
    {
        $request->validate([
            'nama_alat_id' => 'required|exists:nama_alats,id',
            'nomor' => 'required|string|max:255',
            'waktu_inspeksi' => 'nullable|date',
            'durasi_inspeksi' => 'required|integer|min:0',
            'status' => 'nullable|string|max:255'
        ]);

        $namaAlat = NamaAlat::findOrFail($request->nama_alat_id);

        Alat::create([
            'nama_alat_id' => $namaAlat->id,
            'nama_alat' => $namaAlat->nama_alat,
            'nomor' => $request->nomor,
            'waktu_inspeksi' => $request->waktu_inspeksi,
            'durasi_inspeksi' => $request->durasi_inspeksi,
            'status' => $request->status,
        ]);


        return redirect()->route('operator.alat.index')->with('success', 'Alat berhasil ditambahkan.');
    }

    public function operator_alat_edit($id)
    {
        $alat = Alat::findOrFail($id);
        $nama_alats = NamaAlat::all();
        return view('operator.master.alat.alat.edit', compact('alat', 'nama_alats'));
    }

    public function operator_alat_update(Request $request, $id)
    {
        $request->validate([
            'nama_alat' => 'required|exists:nama_alats,nama_alat',
            'nomor' => 'required|string|max:255',
            'waktu_inspeksi' => 'nullable|date',
            'durasi_inspeksi' => 'required|integer|min:0',
            'status' => 'required|string|max:255'
        ]);

        $alat = Alat::findOrFail($id);
        $namaAlat = NamaAlat::findOrFail($request->nama_alat_id);

        $alat->update([
            'nama_alat_id' => $namaAlat->id,
            'nama_alat' => $namaAlat->nama_alat,
            'nomor' => $request->nomor,
            'waktu_inspeksi' => $request->waktu_inspeksi,
            'durasi_inspeksi' => $request->durasi_inspeksi,
            'status' => $request->status,
        ]);

        return redirect()->route('operator.alat.index')->with('success', 'Alat berhasil diperbarui.');
    }

    public function operator_alat_show($id)
    {
        $tool_fixs = SentToolReport::where('alat_id', $id)
            ->orderByDesc('tanggal_pemeriksaan')
            ->get();
        return view('operator.master.alat.alat.show', compact('tool_fixs'));
    }

    public function operator_alat_destroy($id)
    {
        $alat = Alat::findOrFail($id);
        $alat->delete();

        return redirect()->route('operator.alat.index')->with('success', 'Alat berhasil dihapus.');
    }


    // NAMA ALAT MASTER



    public function operator_nama_alat_create()
    {
        return view('operator.master.alat.nama_alat.create');
    }

    public function operator_nama_alat_store(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required|string|unique:nama_alats,nama_alat|max:255',
        ]);

        NamaAlat::create($request->only('nama_alat'));

        return redirect()->route('operator.alat.index')->with('success', 'Nama alat berhasil ditambahkan.');
    }

    public function operator_nama_alat_edit($id)
    {
        $nama_alat = NamaAlat::findOrFail($id);
        return view('operator.master.alat.nama_alat.edit', compact('nama_alat'));
    }

    public function operator_nama_alat_update(Request $request, $id)
    {
        $request->validate([
            'nama_alat' => 'required|string|unique:nama_alats,nama_alat|max:255',
        ]);

        $nama_alat = NamaAlat::findOrFail($id);
        $nama_alat->update($request->only('nama_alat'));

        return redirect()->route('operator.alat.index')->with('success', 'Nama alat berhasil diperbarui.');
    }

    public function operator_nama_alat_show($id)
    {
        $nama_alat = NamaAlat::findOrFail($id);
        return view('operator.master.alat.nama_alat.show', compact('nama_alat'));
    }

    public function operator_nama_alat_destroy($id)
    {
        $nama_alat = NamaAlat::findOrFail($id);
        $nama_alat->delete();

        return redirect()->route('operator.alat.index')->with('success', 'Nama alat berhasil dihapus.');
    }
    
}
