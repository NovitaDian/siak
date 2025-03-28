<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use App\Models\ToolReport;
use App\Models\SentToolReport;
use App\Models\ToolRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToolController extends Controller
{
    // Menampilkan daftar laporan K3
    public function index()
    {
        $tools = ToolReport::all();
        $tool_fixs = SentToolReport::all();
        $requests = ToolRequest::all();
        return view('adminsystem.tool.index', compact('tools','requests','tool_fixs'));
    }

    // Menampilkan form untuk menambah laporan K3
    public function create()
    {
        return view('adminsystem.tool.create');
    }

    // Menyimpan laporan K3 yang baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'alat_terpakai' => 'required|string',
            'kondisi_fisik' => 'required|string',
            'fungsi_kerja' => 'required|string',
            'sertifikasi' => 'required|string',
            'kebersihan' => 'required|string',
            'pemeliharaan' => 'required|string',
            'label_petunjuk' => 'required|string',
            'keamanan_pengguna' => 'required|string',
            'tanggal_pemeriksaan' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['writer'] = auth()->user()->name; // atau auth()->user()->id tergantung kebutuhan

        ToolReport::create($data);


        return redirect()->route('adminsystem.tool.index')->with('success', 'Laporan K3 berhasil ditambahkan.');
    }

    // Menampilkan halaman edit untuk laporan K3 tertentu
    public function edit($id)
    {
        $toolReport = ToolReport::findOrFail($id);
        return view('adminsystem.tool.edit', compact('toolReport'));
    }

    // Memperbarui laporan K3
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'alat_terpakai' => 'required|string',
            'kondisi_fisik' => 'required|string',
            'fungsi_kerja' => 'required|string',
            'sertifikasi' => 'required|string',
            'kebersihan' => 'required|string',
            'label_petunjuk' => 'required|string',
            'pemeliharaan' => 'required|string',
            'keamanan_pengguna' => 'required|string',
            'tanggal_pemeriksaan' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $toolReport = ToolReport::findOrFail($id);
        $toolReport->update($validated);

        return redirect()->route('adminsystem.tool.index')->with('success', 'Laporan K3 berhasil diperbarui.');
    }

    // Menghapus laporan K3
    public function destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $tool = ToolReport::findOrFail($id);

        // Menyiapkan data untuk dimasukkan ke tool_fix, pastikan data diubah menjadi array
        $dataToInsert = $tool->toArray();

        // Memastikan created_at dan updated_at ditambahkan (jika tabel tool_fix memerlukannya)
        $dataToInsert['created_at'] = $tool->created_at;
        $dataToInsert['updated_at'] = $tool->updated_at;

        // Cek apakah data sudah ada di tool_fix berdasarkan ID atau kolom unik lainnya
        $exists = DB::table('tool_report_fix')->where('id', $tool->id)->exists();

        if (!$exists) {
            // Insert data hanya jika belum ada
            DB::table('tool_report_fix')->insert($dataToInsert);
        }

        // Hapus data tool asli
        $tool->delete();

        // Redirect dengan notifikasi
        return redirect()->route('adminsystem.tool.index')->with('notification', 'Laporan berhasil terkirim!');
    }
    public function storeRequest(Request $request)
    {
        // Validasi input
        $request->validate([
            'sent_tool_id' => 'required|exists:tool_report_fix,id', // Pastikan NCR ID ada
            'type' => 'required|in:edit,delete', // Validasi jenis permintaan
            'reason' => 'required|string', // Validasi alasan
        ]);

        // Simpan request ke dalam tabel tool_request
        $toolRequest = ToolRequest::create([
            'sent_tool_id' => $request->sent_tool_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => auth()->user()->name, // Menambahkan nama pengirim
        ]);

        // Redirect dengan pesan sukses
        return view('adminsystem.tool.index');
    }
    public function approve($id)
    {
        $request = ToolRequest::find($id);
        $request->status = 'Approved';
        $request->save();

        return response()->json(['success' => true]);
    }

    public function reject($id)
    {
        $request = ToolRequest::find($id);
        $request->status = 'Rejected';
        $request->save();

        return response()->json(['success' => true]);
    }

}
