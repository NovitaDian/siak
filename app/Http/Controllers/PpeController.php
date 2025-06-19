<?php

namespace App\Http\Controllers;

use App\Exports\PpeExport;
use App\Mail\PPERequestNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\HseInspector;
use App\Models\NonCompliant;
use App\Models\NonCompliantRequest;
use Illuminate\Http\Request;
use App\Models\Ppe;
use App\Models\PpeRequest;
use App\Models\SentPpe;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class PpeController extends Controller
{
    // Menampilkan semua data observasi
    public function index(Request $request)
    {
        $user = Auth::user();
        $ppes = Ppe::where('writer', $user->name)->get();

        $start = $request->start_date;
        $end = $request->end_date;

        // Jika filter tanggal diisi, gunakan whereBetween dan urutkan dari yang terbaru
        if ($start && $end) {
            $ppe_fixs = SentPpe::whereBetween('tanggal_shift_kerja', [$start, $end])
                ->orderBy('tanggal_shift_kerja', 'desc')
                ->get();
        } else {
            $ppe_fixs = SentPpe::orderBy('tanggal_shift_kerja', 'desc')->get();
        }

        $requests = PpeRequest::all();
        return view('adminsystem.PPE.index', compact('ppes', 'ppe_fixs', 'requests'));
    }

    // Menampilkan form untuk membuat data baru
    public function create()
    {
        $inspectors = HseInspector::all();
        return view('adminsystem.PPE.report', compact('inspectors'));
    }

    // Menyimpan data baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'zona_pengawasan' => 'required|string|max:100',
            'lokasi_observasi' => 'required|string|max:100',
            'jumlah_patuh_apd_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_helm_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_sepatu_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => 'nullable|integer|min:0',
            'jumlah_patuh_apd_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_helm_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_sepatu_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => 'nullable|integer|min:0',
            'keterangan_tidak_patuh' => 'nullable|string|max:255',
        ]);

        // Ambil data HSE Inspector
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        // Hitung total tidak patuh
        $total_tidak_patuh =
            ($request->jumlah_tidak_patuh_helm_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_sepatu_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_pelindung_mata_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_safety_harness_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_apd_lainnya_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_helm_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_sepatu_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_pelindung_mata_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_safety_harness_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_apd_lainnya_kontraktor ?? 0);

        // Simpan data ke tabel Ppe
        SentPpe::create([
            'tanggal_shift_kerja' => $request->tanggal_shift_kerja,
            'shift_kerja' => $request->shift_kerja,
            'hse_inspector_id' => $inspector->id,
            'nama_hse_inspector' => $inspector->name,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'zona_pengawasan' => $request->zona_pengawasan,
            'lokasi_observasi' => $request->lokasi_observasi,
            'jumlah_patuh_apd_karyawan' => $request->jumlah_patuh_apd_karyawan,
            'jumlah_tidak_patuh_helm_karyawan' => $request->jumlah_tidak_patuh_helm_karyawan,
            'jumlah_tidak_patuh_sepatu_karyawan' => $request->jumlah_tidak_patuh_sepatu_karyawan,
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => $request->jumlah_tidak_patuh_pelindung_mata_karyawan,
            'jumlah_tidak_patuh_safety_harness_karyawan' => $request->jumlah_tidak_patuh_safety_harness_karyawan,
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => $request->jumlah_tidak_patuh_apd_lainnya_karyawan,
            'jumlah_patuh_apd_kontraktor' => $request->jumlah_patuh_apd_kontraktor,
            'jumlah_tidak_patuh_helm_kontraktor' => $request->jumlah_tidak_patuh_helm_kontraktor,
            'jumlah_tidak_patuh_sepatu_kontraktor' => $request->jumlah_tidak_patuh_sepatu_kontraktor,
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => $request->jumlah_tidak_patuh_pelindung_mata_kontraktor,
            'jumlah_tidak_patuh_safety_harness_kontraktor' => $request->jumlah_tidak_patuh_safety_harness_kontraktor,
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => $request->jumlah_tidak_patuh_apd_lainnya_kontraktor,
            'keterangan_tidak_patuh' => $request->keterangan_tidak_patuh,
            'writer' => Auth::user()->name,
            'user_id' => Auth::user()->id,
            'status_ppe' => ($total_tidak_patuh == 0) ? 'Compliant' : 'Non-Compliant',
        ]);

        return redirect()->route('adminsystem.ppe.index')->with('success', 'Data berhasil disimpan!');
    }



    // Menampilkan detail data
    public function show($id)
    {
        $ppeFix = SentPpe::findOrFail($id);
        $nonCompliants = NonCompliant::where('id_ppe', $id)->get();
        $requests = NonCompliantRequest::all();
        return view('adminsystem.PPE.show', compact('ppeFix', 'requests', 'nonCompliants'));
    }

    // Menampilkan form edit data
    public function edit($id)
    {
        $ppe = Ppe::findOrFail($id);
        $inspectors = HseInspector::all();

        return view('ppe.edit', compact('ppe', 'inspectors'));
    }

    // Mengupdate data ke database
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'zona_pengawasan' => 'required|string|max:100',
            'lokasi_observasi' => 'required|string|max:100',
            'jumlah_patuh_apd_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_helm_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_sepatu_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => 'nullable|integer|min:0',
            'jumlah_patuh_apd_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_helm_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_sepatu_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => 'nullable|integer|min:0',
            'keterangan_tidak_patuh' => 'nullable|string|max:255',
        ]);

        $ppe = Ppe::findOrFail($id);
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        $total_tidak_patuh =
            ($request->jumlah_tidak_patuh_helm_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_sepatu_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_pelindung_mata_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_safety_harness_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_apd_lainnya_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_helm_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_sepatu_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_pelindung_mata_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_safety_harness_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_apd_lainnya_kontraktor ?? 0);
        $status_ppe = ($total_tidak_patuh == 0) ? 'Compliant' : 'Non-Compliant';

        $ppe->update([
            'tanggal_shift_kerja' => $request->tanggal_shift_kerja,
            'shift_kerja' => $request->shift_kerja,
            'hse_inspector_id' => $inspector->id,
            'nama_hse_inspector' => $inspector->name,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'zona_pengawasan' => $request->zona_pengawasan,
            'lokasi_observasi' => $request->lokasi_observasi,
            'jumlah_patuh_apd_karyawan' => $request->jumlah_patuh_apd_karyawan,
            'jumlah_tidak_patuh_helm_karyawan' => $request->jumlah_tidak_patuh_helm_karyawan,
            'jumlah_tidak_patuh_sepatu_karyawan' => $request->jumlah_tidak_patuh_sepatu_karyawan,
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => $request->jumlah_tidak_patuh_pelindung_mata_karyawan,
            'jumlah_tidak_patuh_safety_harness_karyawan' => $request->jumlah_tidak_patuh_safety_harness_karyawan,
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => $request->jumlah_tidak_patuh_apd_lainnya_karyawan,
            'jumlah_patuh_apd_kontraktor' => $request->jumlah_patuh_apd_kontraktor,
            'jumlah_tidak_patuh_helm_kontraktor' => $request->jumlah_tidak_patuh_helm_kontraktor,
            'jumlah_tidak_patuh_sepatu_kontraktor' => $request->jumlah_tidak_patuh_sepatu_kontraktor,
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => $request->jumlah_tidak_patuh_pelindung_mata_kontraktor,
            'jumlah_tidak_patuh_safety_harness_kontraktor' => $request->jumlah_tidak_patuh_safety_harness_kontraktor,
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => $request->jumlah_tidak_patuh_apd_lainnya_kontraktor,
            'keterangan_tidak_patuh' => $request->keterangan_tidak_patuh,
            'writer' => Auth::user()->name,
            'user_id' => Auth::user()->id,
            'status_ppe' => $status_ppe
        ]);

        return redirect()->route('adminsystem.ppe.index')->with('success', 'Data berhasil diperbarui!');
    }


    // Menghapus data dari database
    public function destroy($id)
    {
        $ppe = Ppe::findOrFail($id);

        // Hitung total tidak patuh
        $total_tidak_patuh =
            ($ppe->jumlah_tidak_patuh_helm_karyawan ?? 0) +
            ($ppe->jumlah_tidak_patuh_sepatu_karyawan ?? 0) +
            ($ppe->jumlah_tidak_patuh_pelindung_mata_karyawan ?? 0) +
            ($ppe->jumlah_tidak_patuh_safety_harness_karyawan ?? 0) +
            ($ppe->jumlah_tidak_patuh_apd_lainnya_karyawan ?? 0) +
            ($ppe->jumlah_tidak_patuh_helm_kontraktor ?? 0) +
            ($ppe->jumlah_tidak_patuh_sepatu_kontraktor ?? 0) +
            ($ppe->jumlah_tidak_patuh_pelindung_mata_kontraktor ?? 0) +
            ($ppe->jumlah_tidak_patuh_safety_harness_kontraktor ?? 0) +
            ($ppe->jumlah_tidak_patuh_apd_lainnya_kontraktor ?? 0);

        // Set status PPE
        $status_ppe = ($total_tidak_patuh == 0) ? 'Compliant' : 'Non-Compliant';

        // Pindahkan data ke tabel ppe_fix
        SentPpe::create([
            'draft_id' => $ppe->id,
            'writer' => $ppe->writer,
            'tanggal_shift_kerja' => $ppe->tanggal_shift_kerja,
            'shift_kerja' => $ppe->shift_kerja,
            'nama_hse_inspector' => $ppe->nama_hse_inspector,
            'hse_inspector_id' => $ppe->hse_inspector_id,
            'jam_mulai' => $ppe->jam_mulai,
            'jam_selesai' => $ppe->jam_selesai,
            'zona_pengawasan' => $ppe->zona_pengawasan,
            'lokasi_observasi' => $ppe->lokasi_observasi,
            'jumlah_patuh_apd_karyawan' => $ppe->jumlah_patuh_apd_karyawan,
            'jumlah_tidak_patuh_helm_karyawan' => $ppe->jumlah_tidak_patuh_helm_karyawan,
            'jumlah_tidak_patuh_sepatu_karyawan' => $ppe->jumlah_tidak_patuh_sepatu_karyawan,
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => $ppe->jumlah_tidak_patuh_pelindung_mata_karyawan,
            'jumlah_tidak_patuh_safety_harness_karyawan' => $ppe->jumlah_tidak_patuh_safety_harness_karyawan,
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => $ppe->jumlah_tidak_patuh_apd_lainnya_karyawan,
            'jumlah_patuh_apd_kontraktor' => $ppe->jumlah_patuh_apd_kontraktor,
            'jumlah_tidak_patuh_helm_kontraktor' => $ppe->jumlah_tidak_patuh_helm_kontraktor,
            'jumlah_tidak_patuh_sepatu_kontraktor' => $ppe->jumlah_tidak_patuh_sepatu_kontraktor,
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => $ppe->jumlah_tidak_patuh_pelindung_mata_kontraktor,
            'jumlah_tidak_patuh_safety_harness_kontraktor' => $ppe->jumlah_tidak_patuh_safety_harness_kontraktor,
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => $ppe->jumlah_tidak_patuh_apd_lainnya_kontraktor,
            'keterangan_tidak_patuh' => $ppe->keterangan_tidak_patuh,
            'durasi_ppe' => $ppe->durasi_ppe,
            'status_note' => $ppe->status_note,
            'status_ppe' => $status_ppe,
        ]);

        // Hapus data dari tabel ppe_draft
        $ppe->delete();

        return redirect()->route('adminsystem.ppe.index')->with('notification', 'PPE berhasil dipindahkan ke ppe_fix!');
    }

    public function sent_edit($id)
    {
        // Retrieve the NCR record by ID
        $ppeFixs = SentPpe::findOrFail($id);
        $inspectors = HseInspector::all();

        return view('adminsystem.PPE.sent_edit', compact('ppeFixs', 'inspectors'));
    }
    public function sent_update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'zona_pengawasan' => 'required|string|max:100',
            'lokasi_observasi' => 'required|string|max:100',
            'jumlah_patuh_apd_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_helm_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_sepatu_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => 'nullable|integer|min:0',
            'jumlah_patuh_apd_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_helm_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_sepatu_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => 'nullable|integer|min:0',
            'keterangan_tidak_patuh' => 'nullable|string|max:255',
            'durasi_ppe' => 'nullable|string|max:50',
            'status_note' => 'nullable|string|max:100',
        ]);

        $sent = SentPpe::findOrFail($id);
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        $total_tidak_patuh =
            ($request->jumlah_tidak_patuh_helm_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_sepatu_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_pelindung_mata_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_safety_harness_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_apd_lainnya_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_helm_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_sepatu_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_pelindung_mata_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_safety_harness_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_apd_lainnya_kontraktor ?? 0);

        $sent->update([
            'tanggal_shift_kerja' => $request->tanggal_shift_kerja,
            'shift_kerja' => $request->shift_kerja,
            'hse_inspector_id' => $inspector->id,
            'nama_hse_inspector' => $inspector->name,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'zona_pengawasan' => $request->zona_pengawasan,
            'lokasi_observasi' => $request->lokasi_observasi,
            'jumlah_patuh_apd_karyawan' => $request->jumlah_patuh_apd_karyawan,
            'jumlah_tidak_patuh_helm_karyawan' => $request->jumlah_tidak_patuh_helm_karyawan,
            'jumlah_tidak_patuh_sepatu_karyawan' => $request->jumlah_tidak_patuh_sepatu_karyawan,
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => $request->jumlah_tidak_patuh_pelindung_mata_karyawan,
            'jumlah_tidak_patuh_safety_harness_karyawan' => $request->jumlah_tidak_patuh_safety_harness_karyawan,
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => $request->jumlah_tidak_patuh_apd_lainnya_karyawan,
            'jumlah_patuh_apd_kontraktor' => $request->jumlah_patuh_apd_kontraktor,
            'jumlah_tidak_patuh_helm_kontraktor' => $request->jumlah_tidak_patuh_helm_kontraktor,
            'jumlah_tidak_patuh_sepatu_kontraktor' => $request->jumlah_tidak_patuh_sepatu_kontraktor,
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => $request->jumlah_tidak_patuh_pelindung_mata_kontraktor,
            'jumlah_tidak_patuh_safety_harness_kontraktor' => $request->jumlah_tidak_patuh_safety_harness_kontraktor,
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => $request->jumlah_tidak_patuh_apd_lainnya_kontraktor,
            'keterangan_tidak_patuh' => $request->keterangan_tidak_patuh,
            'durasi_ppe' => $request->durasi_ppe,
            'status_note' => $request->status_note,
            'status_ppe' => ($total_tidak_patuh == 0) ? 'Compliant' : 'Non-Compliant',
            'writer' => Auth::user()->name,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('adminsystem.ppe.index')->with('success', 'Data PPE telah diperbarui!');
    }


    public function sent_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $ppe_fixs = SentPpe::findOrFail($id);
        $ppe_fixs->delete();
        return redirect()->route('adminsystem.ppe.index')->with('notification', 'NCR berhasil dikirim!');
    }
    public function storeRequest(Request $request)
    {
        $request->validate([
            'sent_ppe_id' => 'required|exists:ppe_fix,id',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        $ppeRequest = PpeRequest::create([
            'sent_ppe_id' => $request->sent_ppe_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => Auth::user()->name,
            'user_id' => Auth::user()->id,
            'status' => 'Pending',
        ]);

        SentPpe::where('id', $request->sent_ppe_id)->update([
            'status' => 'Pending',
        ]);

        // Kirim email ke semua adminsystem
        $admins = User::where('role', 'adminsystem')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new PpeRequestNotification($ppeRequest));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Request berhasil dikirim dan email telah dikirim ke admin.',
            ]);
        }

        return redirect()->route('adminsystem.ppe.index')->with('success', 'Request berhasil dikirim dan email telah dikirim ke admin.');
    }
    public function approve($id)
    {
        $request = PpeRequest::findOrFail($id);
        $request->status = 'Approved';
        $request->save();

        // Update juga ppe_fixs jika perlu
        SentPpe::where('id', $request->sent_ppe_id)->update([
            'status' => 'Approved',
        ]);

        return response()->json(['success' => true]);
    }


    public function reject($id)
    {
        $request = PpeRequest::find($id);
        $request->status = 'Rejected';
        $request->save();
        // Update juga ppe_fixs jika perlu
        SentPpe::where('id', $request->sent_ppe_id)->update([
            'status' => 'Rejected',
        ]);
        return response()->json(['success' => true]);
    }
    public function export(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            return Excel::download(new PpeExport($start, $end), 'ppe_filtered.xlsx');
        } else {
            return Excel::download(new PpeExport(null, null), 'ppe_all.xlsx');
        }
    }

    public function exportPdf(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            $ppe_fixs = SentPpe::whereBetween('tanggal_shift_kerja', [$start, $end])->get();
        } else {
            $ppe_fixs = SentPpe::all();
        }

        $pdf = Pdf::loadView('adminsystem.ppe.pdf', compact('ppe_fixs'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('ppe.pdf');
    }


















    // Menampilkan semua data observasi
    public function operator_index(Request $request)
    {
        $user = Auth::user();
        $ppes = Ppe::where('writer', $user->name)->get();

        $start = $request->start_date;
        $end = $request->end_date;

        // Jika filter tanggal diisi, gunakan whereBetween
        if ($start && $end) {
            $ppe_fixs = SentPpe::whereBetween('tanggal_shift_kerja', [$start, $end])
                ->orderBy('tanggal_shift_kerja', 'desc')
                ->get();
        } else {
            $ppe_fixs = SentPpe::where('writer', $user->name)->orderBy('tanggal_shift_kerja', 'desc')
                ->get();
        }

        $requests = PpeRequest::all();
        return view('operator.PPE.index', compact('ppes', 'ppe_fixs', 'requests'));
    }
    // Menampilkan form untuk membuat data baru
    public function operator_create()
    {
        $inspectors = HseInspector::all();
        return view('operator.PPE.report', compact('inspectors'));
    }

    // Menyimpan data baru ke database
    public function operator_store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'zona_pengawasan' => 'required|string|max:100',
            'lokasi_observasi' => 'required|string|max:100',
            'jumlah_patuh_apd_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_helm_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_sepatu_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => 'nullable|integer|min:0',
            'jumlah_patuh_apd_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_helm_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_sepatu_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => 'nullable|integer|min:0',
            'keterangan_tidak_patuh' => 'nullable|string|max:255',
        ]);

        // Ambil data HSE Inspector
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        // Hitung total tidak patuh
        $total_tidak_patuh =
            ($request->jumlah_tidak_patuh_helm_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_sepatu_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_pelindung_mata_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_safety_harness_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_apd_lainnya_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_helm_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_sepatu_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_pelindung_mata_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_safety_harness_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_apd_lainnya_kontraktor ?? 0);

        // Simpan data ke tabel Ppe
        SentPpe::create([
            'tanggal_shift_kerja' => $request->tanggal_shift_kerja,
            'shift_kerja' => $request->shift_kerja,
            'hse_inspector_id' => $inspector->id,
            'nama_hse_inspector' => $inspector->name,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'zona_pengawasan' => $request->zona_pengawasan,
            'lokasi_observasi' => $request->lokasi_observasi,
            'jumlah_patuh_apd_karyawan' => $request->jumlah_patuh_apd_karyawan,
            'jumlah_tidak_patuh_helm_karyawan' => $request->jumlah_tidak_patuh_helm_karyawan,
            'jumlah_tidak_patuh_sepatu_karyawan' => $request->jumlah_tidak_patuh_sepatu_karyawan,
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => $request->jumlah_tidak_patuh_pelindung_mata_karyawan,
            'jumlah_tidak_patuh_safety_harness_karyawan' => $request->jumlah_tidak_patuh_safety_harness_karyawan,
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => $request->jumlah_tidak_patuh_apd_lainnya_karyawan,
            'jumlah_patuh_apd_kontraktor' => $request->jumlah_patuh_apd_kontraktor,
            'jumlah_tidak_patuh_helm_kontraktor' => $request->jumlah_tidak_patuh_helm_kontraktor,
            'jumlah_tidak_patuh_sepatu_kontraktor' => $request->jumlah_tidak_patuh_sepatu_kontraktor,
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => $request->jumlah_tidak_patuh_pelindung_mata_kontraktor,
            'jumlah_tidak_patuh_safety_harness_kontraktor' => $request->jumlah_tidak_patuh_safety_harness_kontraktor,
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => $request->jumlah_tidak_patuh_apd_lainnya_kontraktor,
            'keterangan_tidak_patuh' => $request->keterangan_tidak_patuh,
            'writer' => Auth::user()->name,
            'user_id' => Auth::user()->id,
            'status_ppe' => ($total_tidak_patuh == 0) ? 'Compliant' : 'Non-Compliant',
        ]);

        return redirect()->route('operator.ppe.index')->with('success', 'Data berhasil disimpan!');
    }



    // Menampilkan detail data
    public function operator_show($id)
    {
        $ppeFix = SentPpe::findOrFail($id);
        $nonCompliants = NonCompliant::where('id_ppe', $id)->get();
        $requests = NonCompliantRequest::all();
        return view('operator.PPE.show', compact('ppeFix', 'requests', 'nonCompliants'));
    }

    // Menampilkan form edit data
    public function operator_edit($id)
    {
        $ppe = Ppe::findOrFail($id);
        $inspectors = HseInspector::all();

        return view('ppe.edit', compact('ppe', 'inspectors'));
    }

    // Mengupdate data ke database
    public function operator_update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'zona_pengawasan' => 'required|string|max:100',
            'lokasi_observasi' => 'required|string|max:100',
            'jumlah_patuh_apd_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_helm_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_sepatu_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => 'nullable|integer|min:0',
            'jumlah_patuh_apd_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_helm_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_sepatu_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => 'nullable|integer|min:0',
            'keterangan_tidak_patuh' => 'nullable|string|max:255',
        ]);

        $ppe = Ppe::findOrFail($id);
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        $total_tidak_patuh =
            ($request->jumlah_tidak_patuh_helm_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_sepatu_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_pelindung_mata_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_safety_harness_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_apd_lainnya_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_helm_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_sepatu_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_pelindung_mata_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_safety_harness_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_apd_lainnya_kontraktor ?? 0);
        $status_ppe = ($total_tidak_patuh == 0) ? 'Compliant' : 'Non-Compliant';

        $ppe->update([
            'tanggal_shift_kerja' => $request->tanggal_shift_kerja,
            'shift_kerja' => $request->shift_kerja,
            'hse_inspector_id' => $inspector->id,
            'nama_hse_inspector' => $inspector->name,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'zona_pengawasan' => $request->zona_pengawasan,
            'lokasi_observasi' => $request->lokasi_observasi,
            'jumlah_patuh_apd_karyawan' => $request->jumlah_patuh_apd_karyawan,
            'jumlah_tidak_patuh_helm_karyawan' => $request->jumlah_tidak_patuh_helm_karyawan,
            'jumlah_tidak_patuh_sepatu_karyawan' => $request->jumlah_tidak_patuh_sepatu_karyawan,
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => $request->jumlah_tidak_patuh_pelindung_mata_karyawan,
            'jumlah_tidak_patuh_safety_harness_karyawan' => $request->jumlah_tidak_patuh_safety_harness_karyawan,
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => $request->jumlah_tidak_patuh_apd_lainnya_karyawan,
            'jumlah_patuh_apd_kontraktor' => $request->jumlah_patuh_apd_kontraktor,
            'jumlah_tidak_patuh_helm_kontraktor' => $request->jumlah_tidak_patuh_helm_kontraktor,
            'jumlah_tidak_patuh_sepatu_kontraktor' => $request->jumlah_tidak_patuh_sepatu_kontraktor,
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => $request->jumlah_tidak_patuh_pelindung_mata_kontraktor,
            'jumlah_tidak_patuh_safety_harness_kontraktor' => $request->jumlah_tidak_patuh_safety_harness_kontraktor,
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => $request->jumlah_tidak_patuh_apd_lainnya_kontraktor,
            'keterangan_tidak_patuh' => $request->keterangan_tidak_patuh,
            'writer' => Auth::user()->name,
            'user_id' => Auth::user()->id,
            'status_ppe' => $status_ppe
        ]);

        return redirect()->route('operator.ppe.index')->with('success', 'Data berhasil diperbarui!');
    }


    // Menghapus data dari database
    public function operator_destroy($id)
    {
        $ppe = Ppe::findOrFail($id);

        // Hitung total tidak patuh
        $total_tidak_patuh =
            ($ppe->jumlah_tidak_patuh_helm_karyawan ?? 0) +
            ($ppe->jumlah_tidak_patuh_sepatu_karyawan ?? 0) +
            ($ppe->jumlah_tidak_patuh_pelindung_mata_karyawan ?? 0) +
            ($ppe->jumlah_tidak_patuh_safety_harness_karyawan ?? 0) +
            ($ppe->jumlah_tidak_patuh_apd_lainnya_karyawan ?? 0) +
            ($ppe->jumlah_tidak_patuh_helm_kontraktor ?? 0) +
            ($ppe->jumlah_tidak_patuh_sepatu_kontraktor ?? 0) +
            ($ppe->jumlah_tidak_patuh_pelindung_mata_kontraktor ?? 0) +
            ($ppe->jumlah_tidak_patuh_safety_harness_kontraktor ?? 0) +
            ($ppe->jumlah_tidak_patuh_apd_lainnya_kontraktor ?? 0);

        // Set status PPE
        $status_ppe = ($total_tidak_patuh == 0) ? 'Compliant' : 'Non-Compliant';

        // Pindahkan data ke tabel ppe_fix
        SentPpe::create([
            'draft_id' => $ppe->id,
            'writer' => $ppe->writer,
            'tanggal_shift_kerja' => $ppe->tanggal_shift_kerja,
            'shift_kerja' => $ppe->shift_kerja,
            'nama_hse_inspector' => $ppe->nama_hse_inspector,
            'hse_inspector_id' => $ppe->hse_inspector_id,
            'jam_mulai' => $ppe->jam_mulai,
            'jam_selesai' => $ppe->jam_selesai,
            'zona_pengawasan' => $ppe->zona_pengawasan,
            'lokasi_observasi' => $ppe->lokasi_observasi,
            'jumlah_patuh_apd_karyawan' => $ppe->jumlah_patuh_apd_karyawan,
            'jumlah_tidak_patuh_helm_karyawan' => $ppe->jumlah_tidak_patuh_helm_karyawan,
            'jumlah_tidak_patuh_sepatu_karyawan' => $ppe->jumlah_tidak_patuh_sepatu_karyawan,
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => $ppe->jumlah_tidak_patuh_pelindung_mata_karyawan,
            'jumlah_tidak_patuh_safety_harness_karyawan' => $ppe->jumlah_tidak_patuh_safety_harness_karyawan,
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => $ppe->jumlah_tidak_patuh_apd_lainnya_karyawan,
            'jumlah_patuh_apd_kontraktor' => $ppe->jumlah_patuh_apd_kontraktor,
            'jumlah_tidak_patuh_helm_kontraktor' => $ppe->jumlah_tidak_patuh_helm_kontraktor,
            'jumlah_tidak_patuh_sepatu_kontraktor' => $ppe->jumlah_tidak_patuh_sepatu_kontraktor,
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => $ppe->jumlah_tidak_patuh_pelindung_mata_kontraktor,
            'jumlah_tidak_patuh_safety_harness_kontraktor' => $ppe->jumlah_tidak_patuh_safety_harness_kontraktor,
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => $ppe->jumlah_tidak_patuh_apd_lainnya_kontraktor,
            'keterangan_tidak_patuh' => $ppe->keterangan_tidak_patuh,
            'durasi_ppe' => $ppe->durasi_ppe,
            'status_note' => $ppe->status_note,
            'status_ppe' => $status_ppe,
        ]);

        // Hapus data dari tabel ppe_draft
        $ppe->delete();

        return redirect()->route('operator.ppe.index')->with('notification', 'PPE berhasil dipindahkan ke ppe_fix!');
    }

    public function operator_sent_edit($id)
    {
        // Retrieve the NCR record by ID
        $ppeFixs = SentPpe::findOrFail($id);
        $inspectors = HseInspector::all();

        return view('operator.PPE.sent_edit', compact('ppeFixs', 'inspectors'));
    }
    public function operator_sent_update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'zona_pengawasan' => 'required|string|max:100',
            'lokasi_observasi' => 'required|string|max:100',
            'jumlah_patuh_apd_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_helm_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_sepatu_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_karyawan' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => 'nullable|integer|min:0',
            'jumlah_patuh_apd_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_helm_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_sepatu_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_kontraktor' => 'nullable|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => 'nullable|integer|min:0',
            'keterangan_tidak_patuh' => 'nullable|string|max:255',
            'durasi_ppe' => 'nullable|string|max:50',
            'status_note' => 'nullable|string|max:100',
        ]);

        $sent = SentPpe::findOrFail($id);
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        $total_tidak_patuh =
            ($request->jumlah_tidak_patuh_helm_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_sepatu_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_pelindung_mata_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_safety_harness_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_apd_lainnya_karyawan ?? 0) +
            ($request->jumlah_tidak_patuh_helm_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_sepatu_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_pelindung_mata_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_safety_harness_kontraktor ?? 0) +
            ($request->jumlah_tidak_patuh_apd_lainnya_kontraktor ?? 0);

        $sent->update([
            'tanggal_shift_kerja' => $request->tanggal_shift_kerja,
            'shift_kerja' => $request->shift_kerja,
            'hse_inspector_id' => $inspector->id,
            'nama_hse_inspector' => $inspector->name,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'zona_pengawasan' => $request->zona_pengawasan,
            'lokasi_observasi' => $request->lokasi_observasi,
            'jumlah_patuh_apd_karyawan' => $request->jumlah_patuh_apd_karyawan,
            'jumlah_tidak_patuh_helm_karyawan' => $request->jumlah_tidak_patuh_helm_karyawan,
            'jumlah_tidak_patuh_sepatu_karyawan' => $request->jumlah_tidak_patuh_sepatu_karyawan,
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => $request->jumlah_tidak_patuh_pelindung_mata_karyawan,
            'jumlah_tidak_patuh_safety_harness_karyawan' => $request->jumlah_tidak_patuh_safety_harness_karyawan,
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => $request->jumlah_tidak_patuh_apd_lainnya_karyawan,
            'jumlah_patuh_apd_kontraktor' => $request->jumlah_patuh_apd_kontraktor,
            'jumlah_tidak_patuh_helm_kontraktor' => $request->jumlah_tidak_patuh_helm_kontraktor,
            'jumlah_tidak_patuh_sepatu_kontraktor' => $request->jumlah_tidak_patuh_sepatu_kontraktor,
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => $request->jumlah_tidak_patuh_pelindung_mata_kontraktor,
            'jumlah_tidak_patuh_safety_harness_kontraktor' => $request->jumlah_tidak_patuh_safety_harness_kontraktor,
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => $request->jumlah_tidak_patuh_apd_lainnya_kontraktor,
            'keterangan_tidak_patuh' => $request->keterangan_tidak_patuh,
            'durasi_ppe' => $request->durasi_ppe,
            'status_note' => $request->status_note,
            'status_ppe' => ($total_tidak_patuh == 0) ? 'Compliant' : 'Non-Compliant',
            'writer' => Auth::user()->name,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('operator.sent_ppe.index')->with('success', 'Data PPE telah diperbarui di sent_ppe!');
    }


    public function operator_sent_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $ppe_fixs = SentPpe::findOrFail($id);
        $ppe_fixs->delete();
        return redirect()->route('operator.ppe.index')->with('notification', 'NCR berhasil dikirim!');
    }
    public function operator_storeRequest(Request $request)
    {
        $request->validate([
            'sent_ppe_id' => 'required|exists:ppe_fix,id',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        $ppeRequest = PpeRequest::create([
            'sent_ppe_id' => $request->sent_ppe_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => Auth::user()->name,
            'user_id' => Auth::user()->id,
            'status' => 'Pending',
        ]);

        SentPpe::where('id', $request->sent_ppe_id)->update([
            'status' => 'Pending',
        ]);

        // Kirim email ke semua operator
        $admins = User::where('role', 'operator')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new PpeRequestNotification($ppeRequest));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Request berhasil dikirim dan email telah dikirim ke admin.',
            ]);
        }

        return redirect()->route('operator.ppe.index')->with('success', 'Request berhasil dikirim dan email telah dikirim ke admin.');
    }
    public function operator_approve($id)
    {
        $request = PpeRequest::findOrFail($id);
        $request->status = 'Approved';
        $request->save();

        // Update juga ppe_fixs jika perlu
        SentPpe::where('id', $request->sent_ppe_id)->update([
            'status' => 'Approved',
        ]);

        return response()->json(['success' => true]);
    }


    public function operator_reject($id)
    {
        $request = PpeRequest::find($id);
        $request->status = 'Rejected';
        $request->save();
        // Update juga ppe_fixs jika perlu
        SentPpe::where('id', $request->sent_ppe_id)->update([
            'status' => 'Rejected',
        ]);
        return response()->json(['success' => true]);
    }
    public function operator_export(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            return Excel::download(new PpeExport($start, $end), 'ppe_filtered.xlsx');
        } else {
            return Excel::download(new PpeExport(null, null), 'ppe_all.xlsx');
        }
    }

    public function operator_exportPdf(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            $ppe_fixs = SentPpe::whereBetween('tanggal_shift_kerja', [$start, $end])->get();
        } else {
            $ppe_fixs = SentPpe::all();
        }

        $pdf = Pdf::loadView('operator.ppe.pdf', compact('ppe_fixs'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('ppe.pdf');
    }
}
