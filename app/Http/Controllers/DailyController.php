<?php

namespace App\Http\Controllers;

use App\Exports\DailyExport;
use App\Mail\DailyRequestNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\Daily;
use App\Models\DailyRequest;
use App\Models\HseInspector;
use App\Models\SentDaily;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;

class DailyController extends Controller
{

    // Menampilkan semua data observasi
    public function index(Request $request)
    {
        $user = Auth::user();
        $dailys = Daily::where('writer', $user->name)->get();
 $latestRequests = DailyRequest::orderByDesc('id')
            ->get()
            ->unique('sent_daily_id');
        $start = $request->start_date;
        $end = $request->end_date;

        // Jika filter tanggal diisi, gunakan whereBetween
        if ($start && $end) {
            $daily_fixs = SentDaily::whereBetween('tanggal_shift_kerja', [$start, $end])->get();
        } else {
            $daily_fixs = SentDaily::all();
        }

        $requests = DailyRequest::all();
        return view('adminsystem.daily.index', compact('dailys', 'daily_fixs', 'requests','latestRequests'));
    }


    // Menampilkan form untuk membuat data baru
    public function create()
    {
        $inspectors = HseInspector::all();

        return view('adminsystem.daily.report', compact('inspectors'));
    }

    // Menyimpan data baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'rincian_laporan' => 'nullable|string|max:255',
        ]);

        // Ambil data HSE Inspector
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        // Simpan data ke database
        Daily::create([
            'tanggal_shift_kerja' => $request->tanggal_shift_kerja,
            'shift_kerja' => $request->shift_kerja,
            'nama_hse_inspector' => $inspector->name,
            'hse_inspector_id' => $inspector->id,
            'rincian_laporan' => $request->rincian_laporan,
            'writer' => Auth::user()->name,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('adminsystem.daily.index')->with('success', 'Data berhasil disimpan!');
    }


    // Menampilkan detail data
    public function show($id)
    {
        $daily = Daily::findOrFail($id);
        return view('adminsystem.daily.show', compact('daily'));
    }
    public function sent_show($id)
    {
        $daily = SentDaily::findOrFail($id);
        return view('adminsystem.daily.show', compact('daily'));
    }

    // Menampilkan form edit data
    public function edit($id)
    {
        $daily = Daily::findOrFail($id);
        $inspectors = HseInspector::all();
        return view('adminsystem.daily.edit', compact('daily', 'inspectors'));
    }


    // Mengupdate data ke database
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'rincian_laporan' => 'nullable|string|max:255',
        ]);

        // Ambil data Daily yang akan diupdate
        $daily = Daily::findOrFail($id);

        // Ambil data HSE Inspector
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        // Update data
        $daily->update([
            'tanggal_shift_kerja' => $request->tanggal_shift_kerja,
            'shift_kerja' => $request->shift_kerja,
            'nama_hse_inspector' => $inspector->name,
            'hse_inspector_id' => $inspector->id,
            'rincian_laporan' => $request->rincian_laporan,
            'writer' => Auth::user()->name,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('adminsystem.daily.index')->with('success', 'Data berhasil diupdate!');
    }



    // Menghapus data dari database
    public function destroy($id)
    {
        $daily = Daily::findOrFail($id);

        // Pindahkan data ke tabel daily_fix
        SentDaily::create([
            'draft_id' => $daily->id,
            'writer' => $daily->writer,
            'user_id' => $daily->user_id,
            'alat_id' => $daily->alat_id,
            'tanggal_shift_kerja' => $daily->tanggal_shift_kerja,
            'shift_kerja' => $daily->shift_kerja,
            'nama_hse_inspector' => $daily->nama_hse_inspector,
            'hse_inspector_id' => $daily->hse_inspector_id,
            'rincian_laporan' => $daily->rincian_laporan,

        ]);

        // Hapus data dari daily
        $daily->delete();

        return redirect()->route('adminsystem.daily.index')->with('success', 'Data berhasil dikirim.');
    }

    public function sent_edit($id)
    {
        // Retrieve the NCR record by ID
        $daily_fix = SentDaily::findOrFail($id);
        $inspectors = HseInspector::all();

        return view('adminsystem.daily.sent_edit', compact('daily_fix', 'inspectors'));
    }
    public function sent_update(Request $request, $id)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'rincian_laporan' => 'nullable|string|max:255',
        ]);

        // Ambil data Daily yang akan diupdate
        $daily_fix = SentDaily::findOrFail($id);

        // Ambil data HSE Inspector
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        // Update data
        $daily_fix->update([
            'tanggal_shift_kerja' => $request->tanggal_shift_kerja,
            'shift_kerja' => $request->shift_kerja,
            'nama_hse_inspector' => $inspector->name,
            'hse_inspector_id' => $inspector->id,
            'rincian_laporan' => $request->rincian_laporan,
            'writer' => Auth::user()->name,
            'user_id' => Auth::user()->id,
            'status' => 'Nothing',

        ]);

        return redirect()->route('adminsystem.daily.index')->with('success', 'Data berhasil diupdate!');
    }
    public function draft_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $daily = Daily::findOrFail($id);
        $daily->delete();
        // Redirect dengan notifikasi
        return redirect()->route('adminsystem.daily.index')->with('notification', 'NCR berhasil dihapus!');
    }
    public function sent_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $daily_fixs = SentDaily::findOrFail($id);
        $daily_fixs->delete();
        // Redirect dengan notifikasi
        return redirect()->route('adminsystem.daily.index')->with('notification', 'NCR berhasil dihapus!');
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'sent_daily_id' => 'required|exists:daily_fix,id',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        $dailyRequest = DailyRequest::create([
            'sent_daily_id' => $request->sent_daily_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => Auth::user()->name,
            'user_id' => Auth::user()->id,
            'status' => 'Pending',
        ]);

        SentDaily::where('id', $request->sent_daily_id)->update([
            'status' => 'Pending',
        ]);

        // Kirim email ke semua adminsystem
        $admins = User::where('role', 'adminsystem')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new DailyRequestNotification($dailyRequest));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Request berhasil dikirim dan email telah dikirim ke admin.',
            ]);
        }

        return redirect()->route('adminsystem.daily.index')->with('success', 'Request berhasil dikirim dan email telah dikirim ke admin.');
    }
    public function approve($id)
    {
        $request = DailyRequest::findOrFail($id);
        $request->status = 'Approved';
        $request->save();

        // Update juga daily_fixs jika perlu
        SentDaily::where('id', $request->sent_daily_id)->update([
            'status' => 'Approved',
        ]);

        return redirect()->route('adminsystem.daily.index')->with('success', 'Request berhasil disetujui.');
    }


    public function reject($id)
    {
        $request = DailyRequest::find($id);
        $request->status = 'Rejected';
        $request->save();
        // Update juga daily_fixs jika perlu
        SentDaily::where('id', $request->sent_daily_id)->update([
            'status' => 'Rejected',
        ]);
        return redirect()->route('adminsystem.daily.index')->with('success', 'Request berhasil ditolak.');
    }
    public function export(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            return Excel::download(new DailyExport($start, $end), 'daily_filtered.xlsx');
        } else {
            return Excel::download(new DailyExport(null, null), 'daily_all.xlsx');
        }
    }

    public function exportPdf(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            $daily_fixs = SentDaily::whereBetween('tanggal_shift_kerja', [$start, $end])->get();
        } else {
            $daily_fixs = SentDaily::all();
        }

        $pdf = Pdf::loadView('adminsystem.daily.pdf', compact('daily_fixs'));

        return $pdf->download('daily.pdf');
    }














    public function operator_index(Request $request)
    {
        $user = Auth::user();
        $dailys = Daily::where('writer', $user->name)->get();
        $latestRequests = DailyRequest::orderByDesc('id')
            ->get()
            ->unique('sent_daily_id');
        $start = $request->start_date;
        $end = $request->end_date;

        // Jika filter tanggal diisi, gunakan whereBetween
        if ($start && $end) {
            $daily_fixs = SentDaily::whereBetween('tanggal_shift_kerja', [$start, $end])->get();
        } else {
            $daily_fixs = SentDaily::all();
        }

        $requests = DailyRequest::all();
        return view('operator.daily.index', compact('dailys', 'daily_fixs', 'requests', 'latestRequests'));
    }


    // Menampilkan form untuk membuat data baru
    public function operator_create()
    {
        $inspectors = HseInspector::all();

        return view('operator.daily.report', compact('inspectors'));
    }

    // Menyimpan data baru ke database
    public function operator_store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'rincian_laporan' => 'nullable|string|max:255',
        ]);

        // Ambil data HSE Inspector
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        // Simpan data ke database
        Daily::create([
            'tanggal_shift_kerja' => $request->tanggal_shift_kerja,
            'shift_kerja' => $request->shift_kerja,
            'nama_hse_inspector' => $inspector->name,
            'hse_inspector_id' => $inspector->id,
            'rincian_laporan' => $request->rincian_laporan,
            'writer' => Auth::user()->name,
            'user_id' => Auth::user()->id,
        ]);


        return redirect()->route('operator.daily.index')->with('success', 'Data berhasil disimpan!');
    }


    // Menampilkan detail data
    public function operator_show($id)
    {
        $daily = Daily::findOrFail($id);
        return view('operator.daily.show', compact('daily'));
    }
    public function operator_sent_show($id)
    {
        $daily = SentDaily::findOrFail($id);
        return view('adminsystem.daily.show', compact('daily'));
    }
    // Menampilkan form edit data
    public function operator_edit($id)
    {
        $daily = Daily::findOrFail($id);
        $inspectors = HseInspector::all();
        return view('operator.daily.edit', compact('daily', 'inspectors'));
    }
    // Mengupdate data ke database
    public function operator_update(Request $request, $id)
    {

        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'rincian_laporan' => 'nullable|string|max:255',
        ]);

        // Ambil data Daily yang akan diupdate
        $daily = Daily::findOrFail($id);

        // Ambil data HSE Inspector
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        // Update data
        $daily->update([
            'tanggal_shift_kerja' => $request->tanggal_shift_kerja,
            'shift_kerja' => $request->shift_kerja,
            'nama_hse_inspector' => $inspector->name,
            'hse_inspector_id' => $inspector->id,
            'rincian_laporan' => $request->rincian_laporan,
            'writer' => Auth::user()->name,
            'user_id' => Auth::user()->id,
            'status' => 'Nothing',

        ]);

        return redirect()->route('operator.daily.index')->with('success', 'Data berhasil diupdate!');
    }



    // Menghapus data dari database
    public function operator_destroy($id)
    {
        $daily = Daily::findOrFail($id);

        // Pindahkan data ke tabel daily_fix
        SentDaily::create([
            'draft_id' => $daily->id,
            'writer' => $daily->writer,
            'alat_id' => $daily->alat_id,
            'user_id' => $daily->user_id,
            'tanggal_shift_kerja' => $daily->tanggal_shift_kerja,
            'shift_kerja' => $daily->shift_kerja,
            'nama_hse_inspector' => $daily->nama_hse_inspector,
            'hse_inspector_id' => $daily->hse_inspector_id,
            'rincian_laporan' => $daily->rincian_laporan,

        ]);

        // Hapus data dari daily
        $daily->delete();

        return redirect()->route('operator.daily.index')->with('success', 'Data berhasil dikirim.');
    }


    public function operator_sent_edit($id)
    {
        // Retrieve the NCR record by ID
        $daily_fix = SentDaily::findOrFail($id);
        $inspectors = HseInspector::all();

        return view('operator.daily.sent_edit', compact('daily_fix', 'inspectors'));
    }
    public function operator_sent_update(Request $request, $id)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'rincian_laporan' => 'nullable|string|max:255',
        ]);

        // Ambil data Daily yang akan diupdate
        $daily_fix = SentDaily::findOrFail($id);

        // Ambil data HSE Inspector
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        // Update data
        $daily_fix->update([
            'tanggal_shift_kerja' => $request->tanggal_shift_kerja,
            'shift_kerja' => $request->shift_kerja,
            'nama_hse_inspector' => $inspector->name,
            'hse_inspector_id' => $inspector->id,
            'rincian_laporan' => $request->rincian_laporan,
            'writer' => Auth::user()->name,
            'user_id' => Auth::user()->id,
            'status' => 'Nothing',

        ]);

        return redirect()->route('operator.daily.index')->with('success', 'Data berhasil diupdate!');
    }
    public function operator_draft_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $daily = Daily::findOrFail($id);
        $daily->delete();
        // Redirect dengan notifikasi
        return redirect()->route('operator.daily.index')->with('notification', 'NCR berhasil dihapus!');
    }
    public function operator_sent_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $daily_fixs = SentDaily::findOrFail($id);
        $daily_fixs->delete();
        // Redirect dengan notifikasi
        return redirect()->route('operator.daily.index')->with('notification', 'NCR berhasil dihapus!');
    }

    public function operator_storeRequest(Request $request)
    {
        $request->validate([
            'sent_daily_id' => 'required|exists:daily_inspections_fix,id',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        $dailyRequest = DailyRequest::create([
            'sent_daily_id' => $request->sent_daily_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => Auth::user()->name,
            'user_id' => Auth::user()->id,
            'status' => 'Pending',
        ]);

        SentDaily::where('id', $request->sent_daily_id)->update([
            'status' => 'Pending',
        ]);

        // Kirim email ke semua operator
        $admins = User::where('role', 'operator')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new DailyRequestNotification($dailyRequest));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Request berhasil dikirim dan email telah dikirim ke admin.',
            ]);
        }

        return redirect()->route('operator.daily.index')->with('success', 'Request berhasil dikirim dan email telah dikirim ke admin.');
    }
    public function operator_approve($id)
    {
        $request = DailyRequest::findOrFail($id);
        $request->status = 'Approved';
        $request->save();

        // Update juga daily_fixs jika perlu
        SentDaily::where('id', $request->sent_daily_id)->update([
            'status' => 'Approved',
        ]);

        return response()->json(['success' => true]);
    }


    public function operator_reject($id)
    {
        $request = DailyRequest::find($id);
        $request->status = 'Rejected';
        $request->save();
        // Update juga daily_fixs jika perlu
        SentDaily::where('id', $request->sent_daily_id)->update([
            'status' => 'Rejected',
        ]);
        return response()->json(['success' => true]);
    }
    public function operator_export(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            return Excel::download(new DailyExport($start, $end), 'daily_filtered.xlsx');
        } else {
            return Excel::download(new DailyExport(null, null), 'daily_all.xlsx');
        }
    }

    public function operator_exportPdf(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            $daily_fixs = SentDaily::whereBetween('tanggal_shift_kerja', [$start, $end])->get();
        } else {
            $daily_fixs = SentDaily::all();
        }

        $pdf = Pdf::loadView('operator.daily.pdf', compact('daily_fixs'));

        return $pdf->download('daily.pdf');
    }
}
