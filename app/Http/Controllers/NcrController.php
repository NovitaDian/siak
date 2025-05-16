<?php

namespace App\Http\Controllers;

use App\Exports\NcrExport;
use App\Mail\NcrRequestNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\Bagian;
use App\Models\Ncr;
use App\Models\NcrRequest;
use App\Models\NonCompliant;
use App\Models\Perusahaan;
use App\Models\SentNcr;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class NcrController extends Controller
{
    // Menampilkan semua data NCR
    public function index(Request $request)
    {
        $requests = NcrRequest::all();
        $user = Auth::user();
        $ncrs = Ncr::where('writer', $user->name)->get();

        // Ambil semua NCR dan hitung warna berdasarkan durasi
        $start = $request->start_date;
        $end = $request->end_date;

        // Jika filter tanggal diisi, gunakan whereBetween
        if ($start && $end) {
            $ncr_fixs = SentNcr::whereBetween('tanggal_shift_kerja', [$start, $end])->get();
        } else {
            $ncr_fixs = SentNcr::all();
        }


        return view('adminsystem.ncr.index', compact('ncrs', 'ncr_fixs', 'requests'));
    }

    // Menampilkan detail data NCR berdasarkan ID
    public function show($id)
{
    $ncr = SentNcr::findOrFail($id);

    if (is_string($ncr->kategori_ketidaksesuaian)) {
        $ncr->kategori_ketidaksesuaian = explode(',', $ncr->kategori_ketidaksesuaian);
    }

    $tanggalHariIni = Carbon::now()->format('d F Y');

    return view('adminsystem.ncr.show', compact('ncr', 'tanggalHariIni'));
}


    public function edit($id)
    {
        // Retrieve the NCR record by ID
        $ncr = Ncr::findOrFail($id);

        // Retrieve all companies and sections
        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();

        // Pass the data to the edit view
        return view('adminsystem.ncr.edit', compact('ncr', 'perusahaans', 'bagians'));
    }
    public function sent_edit($id)
    {
        // Retrieve the NCR record by ID
        $ncr_fixs = SentNcr::findOrFail($id);

        // Retrieve all companies and sections
        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();

        // Pass the data to the edit view
        return view('adminsystem.ncr.sent_edit', compact('ncr_fixs', 'perusahaans', 'bagians'));
    }

    public function create()
    {
        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();

        // Mengirim data ke view
        return view('adminsystem.ncr.report', compact('perusahaans', 'bagians'));
    }
    // Menambahkan data NCR baru
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:255',
            'nama_hs_officer_1' => 'nullable|string|max:255',
            'nama_hs_officer_2' => 'nullable|string|max:255',
            'tanggal_audit' => 'required|date',
            'nama_auditee' => 'required|string|max:255',
            'perusahaan' => 'required|string|max:255',
            'bagian' => 'nullable|string|max:255',
            'element_referensi_ncr' => 'required|string|max:255',
            'kategori_ketidaksesuaian' => 'required|string|max:255',
            'estimasi' => 'required|string',
            'tindak_lanjut' => 'required|string',
            'foto' => 'required|file|mimes:pdf,jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['writer'] = Auth::user()->name;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoPath = $foto->store('uploads/foto', 'public');
            $data['foto'] = $fotoPath;
        }

        Ncr::create($data);

        return redirect()->route('adminsystem.ncr.index')->with('success', 'Data berhasil disimpan!');
    }


    // Memperbarui data NCR yang ada
    public function update(Request $request, $id)
    {
        $ncr = Ncr::find($id);

        if (!$ncr) {
            return response()->json(['message' => 'Data NCR tidak ditemukan'], 404);
        }

        // Validasi data yang diterima
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:255',
            'nama_hs_officer_1' => 'nullable|string|max:255',
            'nama_hs_officer_2' => 'nullable|string|max:255',
            'tanggal_audit' => 'required|date',
            'nama_auditee' => 'required|string|max:255',
            'perusahaan' => 'required|string|max:255',
            'bagian' => 'nullable|string|max:255',
            'element_referensi_ncr' => 'required|string|max:255',
            'kategori_ketidaksesuaian' => 'required|string|max:255',
            'deskripsi_ketidaksesuaian' => 'required|string',
        ]);

        $ncr = Ncr::findOrFail($id);
        $ncr->update($request->all());

        return redirect()->route('adminsystem.ncr.index')->with('success', 'Data berhasil diupdate!');
    }
    public function sent_update(Request $request, $id)
    {
        $ncr_fixs = SentNcr::find($id);

        if (!$ncr_fixs) {
            return response()->json(['message' => 'Data NCR tidak ditemukan'], 404);
        }

        // Validasi data yang diterima
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:255',
            'nama_hs_officer_1' => 'nullable|string|max:255',
            'nama_hs_officer_2' => 'nullable|string|max:255',
            'tanggal_audit' => 'required|date',
            'nama_auditee' => 'required|string|max:255',
            'perusahaan' => 'required|string|max:255',
            'bagian' => 'nullable|string|max:255',
            'element_referensi_ncr' => 'required|string|max:255',
            'kategori_ketidaksesuaian' => 'required|string|max:255',
            'deskripsi_ketidaksesuaian' => 'required|string',
        ]);

        $ncr_fixs = Ncr::findOrFail($id);
        $ncr_fixs->update($request->all());

        return redirect()->route('adminsystem.ncr.index')->with('success', 'Data berhasil diupdate!');
    }
    // Menghapus data NCR berdasarkan ID
    public function destroy($id)
    {
        $ncr = Ncr::findOrFail($id);

        // Pindahkan data ke tabel ncr_fix
        SentNcr::create([
            'draft_id' => $ncr->id,
            'writer' => $ncr->writer,
            'tanggal_shift_kerja' => $ncr->tanggal_shift_kerja,
            'shift_kerja' => $ncr->shift_kerja,
            'nama_hs_officer_1' => $ncr->nama_hs_officer_1,
            'nama_hs_officer_2' => $ncr->nama_hs_officer_2,
            'tanggal_audit' => $ncr->tanggal_audit,
            'nama_auditee' => $ncr->nama_auditee,
            'perusahaan' => $ncr->perusahaan,
            'nama_bagian' => $ncr->nama_bagian,
            'element_referensi_ncr' => $ncr->element_referensi_ncr,
            'kategori_ketidaksesuaian' => $ncr->kategori_ketidaksesuaian,
            'deskripsi_ketidaksesuaian' => $ncr->deskripsi_ketidaksesuaian,
            'estimasi' => $ncr->estimasi,
            'foto' => $ncr->foto,
            'tindak_lanjut' => $ncr->tindak_lanjut,
            'status' => 'Nothing',
            'status_note' => null,
            'status_ncr' => 'Open',
            'durasi_ncr' => null,
        ]);

        // Hapus data dari ncr
        $ncr->delete();

        return redirect()->route('adminsystem.ncr.index')->with('success', 'Data berhasil dikirim.');
    }

    public function sent_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $ncr_fixs = SentNcr::findOrFail($id);
        $ncr_fixs->delete();
        // Redirect dengan notifikasi
        return redirect()->route('adminsystem.ncr.index')->with('notification', 'NCR berhasil dikirim!');
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'sent_ncr_id' => 'required|exists:ncr_fix,id',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        $ncrRequest = NcrRequest::create([
            'sent_ncr_id' => $request->sent_ncr_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => Auth::user()->name,
            'status' => 'Pending',
        ]);

        SentNcr::where('id', $request->sent_ncr_id)->update([
            'status' => 'Pending',
        ]);

        // Kirim email ke semua adminsystem
        $admins = User::where('role', 'adminsystem')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NcrRequestNotification($ncrRequest));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Request berhasil dikirim dan email telah dikirim ke admin.',
            ]);
        }

        return redirect()->route('adminsystem.ncr.index')->with('success', 'Request berhasil dikirim dan email telah dikirim ke admin.');
    }
    public function approve($id)
    {
        $request = NcrRequest::findOrFail($id);
        $request->status = 'Approved';
        $request->save();

        SentNcr::where('id', $request->sent_ncr_id)->update([
            'status' => 'Approved',
        ]);

        return response()->json(['success' => true]);
    }


    public function reject($id)
    {
        $request = NcrRequest::find($id);
        $request->status = 'Rejected';
        $request->save();
        // Update juga ncr_fixs jika perlu
        SentNcr::where('id', $request->sent_ncr_id)->update([
            'status' => 'Rejected',
        ]);
        return response()->json(['success' => true]);
    }
    public function export(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            return Excel::download(new NcrExport($start, $end), 'ncr_filtered.xlsx');
        } else {
            return Excel::download(new NcrExport(null, null), 'ncr_all.xlsx');
        }
    }
    public function exportPdf(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            $ncr_fixs = SentNcr::whereBetween('tanggal_shift_kerja', [$start, $end])->get();
        } else {
            $ncr_fixs = SentNcr::all();
        }

        $pdf = Pdf::loadView('adminsystem.ncr.pdf', compact('ncr_fixs'))
            ->setPaper('a4', 'landscape');;

        return $pdf->download('ncr.pdf');
    }
    public function close($id)
    {
        // Retrieve the NCR record by ID
        $ncr_fixs = SentNcr::findOrFail($id);

        // Retrieve all companies and sections
        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();

        // Pass the data to the edit view
        return view('adminsystem.ncr.closed_ncr', compact('ncr_fixs', 'perusahaans', 'bagians'));
    }
    public function close_ncr(Request $request, $id)
    {
        $ncr_fix = SentNcr::find($id);

        if (!$ncr_fix) {
            return response()->json(['message' => 'Data NCR tidak ditemukan'], 404);
        }

        // Validasi field-field lain, kecuali `status_ncr` dan `durasi_ncr` yang akan diisi otomatis
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:255',
            'nama_hs_officer_1' => 'nullable|string|max:255',
            'nama_hs_officer_2' => 'nullable|string|max:255',
            'tanggal_audit' => 'required|date',
            'nama_auditee' => 'required|string|max:255',
            'perusahaan' => 'required|string|max:255',
            'nama_bagian' => 'nullable|string|max:255',
            'element_referensi_ncr' => 'required|string|max:255',
            'kategori_ketidaksesuaian' => 'required|string|max:255',
            'deskripsi_ketidaksesuaian' => 'required|string',
            'status_note' => 'required|string',

        ]);

        // Hitung durasi
        $createdAt = Carbon::parse($ncr_fix->created_at);
        $now = Carbon::now();
        $diff = $createdAt->diff($now);

        $durasi = sprintf(
            '%04d-%02d-%02d %02d:%02d:%02d',
            $diff->y,
            $diff->m,
            $diff->d,
            $diff->h,
            $diff->i,
            $diff->s
        );

        // Update data
        $ncr_fix->update([
            'tanggal_shift_kerja' => $request->tanggal_shift_kerja,
            'shift_kerja' => $request->shift_kerja,
            'nama_hs_officer_1' => $request->nama_hs_officer_1,
            'nama_hs_officer_2' => $request->nama_hs_officer_2,
            'tanggal_audit' => $request->tanggal_audit,
            'nama_auditee' => $request->nama_auditee,
            'perusahaan' => $request->perusahaan,
            'nama_bagian' => $request->bagian,
            'element_referensi_ncr' => $request->element_referensi_ncr,
            'kategori_ketidaksesuaian' => $request->kategori_ketidaksesuaian,
            'deskripsi_ketidaksesuaian' => $request->deskripsi_ketidaksesuaian,
            'status_ncr' => 'Closed',
            'status_note' => $request->status_note,
            'durasi_ncr' => $durasi,
            'estimasi' => $request->estimasi,
            'foto' => $request->foto,
            'foto_closed' => $request->foto_closed,
            'tindak_lanjut' => $request->tindak_lanjut,
        ]);

        return redirect()->route('adminsystem.ncr.index')->with('success', 'NCR berhasil di-close!');
    }
}
