<?php

namespace App\Http\Controllers;

use App\Exports\ToolExport;
use App\Mail\ToolRequestNotification;
use App\Models\Alat;
use App\Models\Daily;
use App\Models\HseInspector;
use App\Models\ToolReport;
use App\Models\SentToolReport;
use App\Models\ToolRequest;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ToolController extends Controller
{

    public function index(Request $request)
    {

        $user = Auth::user();
        $tools = ToolReport::with('alat')
            ->where('writer', $user->name)
            ->get();
        $start = $request->start_date;
        $end = $request->end_date;

        // Jika filter tanggal diisi, gunakan whereBetween
        if ($start && $end) {
            $tool_fixs = SentToolReport::whereBetween('tanggal_pemeriksaan', [$start, $end])->get();
        } else {
            $tool_fixs = SentToolReport::all();
        }
        $requests = ToolRequest::all();

        return view('adminsystem.tool.index', compact('tools', 'tool_fixs', 'requests'));
    }
    public function create()
    {
        $alats = Alat::all();
        $inspectors = HseInspector::all();
        return view('adminsystem.tool.create', compact('alats', 'inspectors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'tanggal_pemeriksaan' => 'required|date',
            'status_pemeriksaan' => 'required|in:Layak operasi,Layak operasi dengan catatan,Tidak layak operasi',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'

        ]);

        $alat = Alat::findOrFail($request->alat_id);
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pelanggar/foto', 'public');
            $data['foto'] = $fotoPath;
        }

        ToolReport::create([
            'alat_id' => $alat->id,
            'nama_alat' => $alat->nama_alat,
            'hse_inspector_id' => $inspector->id,
            'hse_inspector' => $inspector->name,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'status_pemeriksaan' => $request->status_pemeriksaan,
            'foto' => $request->foto,
            'writer' => Auth::user()->name,

        ]);

        return redirect()->route('adminsystem.tool.index')->with('success', 'Data pemeriksaan berhasil disimpan.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'tanggal_pemeriksaan' => 'required|date',
            'status_pemeriksaan' => 'required|in:Layak operasi,Layak operasi dengan catatan,Tidak layak operasi',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'

        ]);

        $toolReport = ToolReport::findOrFail($id);
        if ($request->hasFile('foto')) {
            // Optional: delete old file first
            if ($request->foto && Storage::disk('public')->exists($request->foto)) {
                Storage::disk('public')->delete($request->foto);
            }

            $foto = $request->file('foto');
            $fotoPath = $foto->store('uploads/foto', 'public');
            $data['foto'] = $fotoPath;
        } else {
            // Remove 'foto' key if no new file uploaded
            unset($data['foto']);
        }

        $toolReport->update([
            'alat_id' => $request->alat_id,
            'nama_alat' => Alat::find($request->alat_id)?->nama_alat,
            'hse_inspector_id' => $request->hse_inspector_id,
            'hse_inspector' => HseInspector::find($request->hse_inspector_id)?->name,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'status_pemeriksaan' => $request->status_pemeriksaan,
            'writer' => Auth::user()->name,
        ]);

        return redirect()->route('adminsystem.tool.index')->with('success', 'Data pemeriksaan berhasil diperbarui.');
    }
    public function sent_update(Request $request, $id)
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'tanggal_pemeriksaan' => 'required|date',
            'status_pemeriksaan' => 'required|in:Layak operasi,Layak operasi dengan catatan,Tidak layak operasi',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'  // Foto hanya dibutuhkan jika ada file yang diunggah
        ]);

        $tool_fixs = SentToolReport::findOrFail($id);
        $alat = Alat::findOrFail($request->alat_id);
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        $data = [
            'alat_id' => $alat->id,
            'nama_alat' => $alat->nama_alat,
            'hse_inspector_id' => $inspector->id,
            'hse_inspector' => $inspector->name,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'status_pemeriksaan' => $request->status_pemeriksaan,
            'status' => 'Nothing',
            'writer' => Auth::user()->name,
        ];

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($tool_fixs->foto && Storage::disk('public')->exists($tool_fixs->foto)) {
                Storage::disk('public')->delete($tool_fixs->foto);
            }

            // Simpan foto baru
            $foto = $request->file('foto');
            $fotoPath = $foto->store('uploads/foto', 'public');
            $data['foto'] = $fotoPath;
        }

        // Perbarui data
        $tool_fixs->update($data);
        $alat->update([
            'waktu_inspeksi' => $tool_fixs->tanggal_pemeriksaan,
            'status' => $tool_fixs->status_pemeriksaan,
        ]);

        return redirect()->route('adminsystem.tool.index')->with('success', 'Data pemeriksaan berhasil diperbarui.');
    }


    public function edit($id)
    {
        $toolReport = ToolReport::findOrFail($id);
        $alats = Alat::all();
        $inspectors = HseInspector::all();
        return view('adminsystem.tool.edit', compact('alats', 'inspectors', 'toolReport'));
    }
    public function sent_edit($id)
    {
        $tool_fixs = SentToolReport::findOrFail($id);
        $alats = Alat::all();
        $inspectors = HseInspector::all();
        return view('adminsystem.tool.sent_edit', compact('alats', 'inspectors', 'tool_fixs'));
    }
    public function show($id)
    {
        $tools = SentToolReport::find($id);
        $alats = Alat::all();
        $inspectors = HseInspector::all();
        return view('adminsystem.tool.show', compact('alats', 'inspectors', 'tools'));
    }


    public function destroy($id)
    {
        $tool = ToolReport::findOrFail($id);

        // Pindahkan data ke tabel tool_fix
        SentToolReport::create([
            'draft_id' => $tool->id,
            'writer' => $tool->writer,
            'alat_id' => $tool->alat_id,
            'nama_alat' => $tool->nama_alat,
            'hse_inspector_id' => $tool->hse_inspector_id,
            'hse_inspector' => $tool->hse_inspector,
            'tanggal_pemeriksaan' => $tool->tanggal_pemeriksaan,
            'status_pemeriksaan' => $tool->status_pemeriksaan,
            'foto' => $tool->foto,

        ]);
        $alat = Alat::findOrFail($tool->alat_id);
        $alat->update([
            'waktu_inspeksi' => $tool->tanggal_pemeriksaan,
            'status' => $tool->status_pemeriksaan,

        ]);
        // Hapus data dari tool
        $tool->delete();

        return redirect()->route('adminsystem.tool.index')->with('success', 'Data berhasil dikirim.');
    }
    public function sent_destroy(Request $request, $id)
    {
        // Ambil data PPE berdasarkan ID
        $tool_fixs = SentToolReport::findOrFail($id);
        $tool_fixs->delete();
        // Redirect dengan notifikasi
        SentToolReport::where('id', $request->sent_tool_id)->update([
            'status' => 'Nothing',
        ]);
        return redirect()->route('adminsystem.tool.index')->with('notification', 'NCR berhasil dikirim!');
    }
    public function storeRequest(Request $request)
    {
        $request->validate([
            'sent_tool_id' => 'required|exists:tool_inspections_fix,id',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        $toolRequest = ToolRequest::create([
            'sent_tool_id' => $request->sent_tool_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => Auth::user()->name,
            'status' => 'Pending',
        ]);

        SentToolReport::where('id', $request->sent_tool_id)->update([
            'status' => 'Pending',
        ]);

        // Kirim email ke semua adminsystem
        $admins = User::where('role', 'adminsystem')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new ToolRequestNotification($toolRequest));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Request berhasil dikirim dan email telah dikirim ke admin.',
            ]);
        }

        return redirect()->route('adminsystem.tool.index')->with('success', 'Request berhasil dikirim dan email telah dikirim ke admin.');
    }
    public function approve($sent_tool_id)
    {
        $request = ToolRequest::findOrFail($sent_tool_id);
        $request->status = 'Approved';
        $request->save();

        SentToolReport::where('id', $request->sent_tool_id)->update([
            'status' => 'Approved',
        ]);

        return redirect()->route('adminsystem.tool.index');
    }


    public function reject($id)
    {
        $request = ToolRequest::find($id);
        $request->status = 'Rejected';
        $request->save();
        // Update juga tool_fixs jika perlu
        SentToolReport::where('id', $request->sent_tool_id)->update([
            'status' => 'Rejected',
        ]);
        return redirect()->route('adminsystem.tool.index');
    }
    public function export(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            return Excel::download(new ToolExport($start, $end), 'tool_filtered.xlsx');
        } else {
            return Excel::download(new ToolExport(null, null), 'tool_all.xlsx');
        }
    }
    public function exportPdf(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            $tool_fixs = SentToolReport::whereBetween('tanggal_pemeriksaan', [$start, $end])->get();
        } else {
            $tool_fixs = SentToolReport::all();
        }

        $pdf = Pdf::loadView('adminsystem.tool.pdf', compact('tool_fixs'))
            ->setPaper('a4', 'landscape');;

        return $pdf->download('tool.pdf');
    }













    public function operator_index(Request $request)
    {
        $user = Auth::user();

        // Ambil data draft NCR oleh user login
        $tools = ToolReport::where('writer', $user->name)->get();

        // Ambil semua request (Edit/Delete)
        $allRequests = ToolRequest::all();

        // Filter tanggal jika ada
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            $tool_fixs = SentToolReport::whereBetween('tanggal_pemeriksaan', [$start, $end])
                ->where('writer', $user->name)
                ->get();
        } else {
            $tool_fixs = SentToolReport::where('writer', $user->name)->get();
        }

        return view('operator.tool.index', compact('tools', 'tool_fixs', 'allRequests'));
    }
    public function operator_create()
    {
        $alats = Alat::all();
        $inspectors = HseInspector::all();
        return view('operator.tool.create', compact('alats', 'inspectors'));
    }

    public function operator_store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'tanggal_pemeriksaan' => 'required|date',
            'status_pemeriksaan' => 'required|in:Layak operasi,Layak operasi dengan catatan,Tidak layak operasi',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'

        ]);

        $alat = Alat::findOrFail($request->alat_id);
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pelanggar/foto', 'public');
            $data['foto'] = $fotoPath;
        }

        ToolReport::create([
            'alat_id' => $alat->id,
            'nama_alat' => $alat->nama_alat,
            'hse_inspector_id' => $inspector->id,
            'hse_inspector' => $inspector->name,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'status_pemeriksaan' => $request->status_pemeriksaan,
            'foto' => $request->foto,
            'writer' => Auth::user()->name,

        ]);

        return redirect()->route('operator.tool.index')->with('success', 'Data pemeriksaan berhasil disimpan.');
    }
    public function operator_update(Request $request, $id)
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'tanggal_pemeriksaan' => 'required|date',
            'status_pemeriksaan' => 'required|in:Layak operasi,Layak operasi dengan catatan,Tidak layak operasi',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'

        ]);

        $toolReport = ToolReport::findOrFail($id);
        if ($request->hasFile('foto')) {
            // Optional: delete old file first
            if ($request->foto && Storage::disk('public')->exists($request->foto)) {
                Storage::disk('public')->delete($request->foto);
            }

            $foto = $request->file('foto');
            $fotoPath = $foto->store('uploads/foto', 'public');
            $data['foto'] = $fotoPath;
        } else {
            // Remove 'foto' key if no new file uploaded
            unset($data['foto']);
        }

        $toolReport->update([
            'alat_id' => $request->alat_id,
            'nama_alat' => Alat::find($request->alat_id)?->nama_alat,
            'hse_inspector_id' => $request->hse_inspector_id,
            'hse_inspector' => HseInspector::find($request->hse_inspector_id)?->name,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'status_pemeriksaan' => $request->status_pemeriksaan,
            'writer' => Auth::user()->name,
        ]);

        return redirect()->route('operator.tool.index')->with('success', 'Data pemeriksaan berhasil diperbarui.');
    }
    public function operator_sent_update(Request $request, $id)
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'hse_inspector_id' => 'required|exists:hse_inspector,id',
            'tanggal_pemeriksaan' => 'required|date',
            'status_pemeriksaan' => 'required|in:Layak operasi,Layak operasi dengan catatan,Tidak layak operasi',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'  // Foto hanya dibutuhkan jika ada file yang diunggah
        ]);

        $tool_fixs = SentToolReport::findOrFail($id);
        $alat = Alat::findOrFail($request->alat_id);
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        $data = [
            'alat_id' => $alat->id,
            'nama_alat' => $alat->nama_alat,
            'hse_inspector_id' => $inspector->id,
            'hse_inspector' => $inspector->name,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'status_pemeriksaan' => $request->status_pemeriksaan,
            'status' => 'Nothing',
            'writer' => Auth::user()->name,
        ];

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($tool_fixs->foto && Storage::disk('public')->exists($tool_fixs->foto)) {
                Storage::disk('public')->delete($tool_fixs->foto);
            }

            // Simpan foto baru
            $foto = $request->file('foto');
            $fotoPath = $foto->store('uploads/foto', 'public');
            $data['foto'] = $fotoPath;
        }

        // Perbarui data
        $tool_fixs->update($data);
        $alat->update([
            'waktu_inspeksi' => $tool_fixs->tanggal_pemeriksaan,
            'status' => $tool_fixs->status_pemeriksaan,
        ]);

        return redirect()->route('operator.tool.index')->with('success', 'Data pemeriksaan berhasil diperbarui.');
    }


    public function operator_edit($id)
    {
        $toolReport = ToolReport::findOrFail($id);
        $alats = Alat::all();
        $inspectors = HseInspector::all();
        return view('operator.tool.edit', compact('alats', 'inspectors', 'toolReport'));
    }
    public function operator_sent_edit($id)
    {
        $tool_fixs = SentToolReport::findOrFail($id);
        $alats = Alat::all();
        $inspectors = HseInspector::all();
        return view('operator.tool.sent_edit', compact('alats', 'inspectors', 'tool_fixs'));
    }
    public function operator_show($id)
    {
        $tools = SentToolReport::find($id);
        $alats = Alat::all();
        $inspectors = HseInspector::all();
        return view('operator.tool.show', compact('alats', 'inspectors', 'tools'));
    }


    public function operator_destroy($id)
    {
        $tool = ToolReport::findOrFail($id);

        // Pindahkan data ke tabel tool_fix
        SentToolReport::create([
            'draft_id' => $tool->id,
            'writer' => $tool->writer,
            'alat_id' => $tool->alat_id,
            'nama_alat' => $tool->nama_alat,
            'hse_inspector_id' => $tool->hse_inspector_id,
            'hse_inspector' => $tool->hse_inspector,
            'tanggal_pemeriksaan' => $tool->tanggal_pemeriksaan,
            'status_pemeriksaan' => $tool->status_pemeriksaan,
            'foto' => $tool->foto,

        ]);
        $alat = Alat::findOrFail($tool->alat_id);
        $alat->update([
            'waktu_inspeksi' => $tool->tanggal_pemeriksaan,
            'status' => $tool->status_pemeriksaan,

        ]);
        // Hapus data dari tool
        $tool->delete();

        return redirect()->route('operator.tool.index')->with('success', 'Data berhasil dikirim.');
    }
    public function operator_sent_destroy(Request $request, $id)
    {
        // Ambil data PPE berdasarkan ID
        $tool_fixs = SentToolReport::findOrFail($id);
        $tool_fixs->delete();
        // Redirect dengan notifikasi
        SentToolReport::where('id', $request->sent_tool_id)->update([
            'status' => 'Nothing',
        ]);
        return redirect()->route('operator.tool.index')->with('notification', 'NCR berhasil dikirim!');
    }
    public function operator_storeRequest(Request $request)
    {
        $request->validate([
            'sent_tool_id' => 'required|exists:tool_inspections_fix,id',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        $toolRequest = ToolRequest::create([
            'sent_tool_id' => $request->sent_tool_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => Auth::user()->name,
            'status' => 'Pending',
        ]);

        SentToolReport::where('id', $request->sent_tool_id)->update([
            'status' => 'Pending',
        ]);

        // Kirim email ke semua operator
        $admins = User::where('role', 'operator')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new ToolRequestNotification($toolRequest));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Request berhasil dikirim dan email telah dikirim ke admin.',
            ]);
        }

        return redirect()->route('operator.tool.index')->with('success', 'Request berhasil dikirim dan email telah dikirim ke admin.');
    }
    public function operator_approve($sent_tool_id)
    {
        $request = ToolRequest::findOrFail($sent_tool_id);
        $request->status = 'Approved';
        $request->save();

        SentToolReport::where('id', $request->sent_tool_id)->update([
            'status' => 'Approved',
        ]);

        return redirect()->route('operator.tool.index');
    }


    public function operator_reject($id)
    {
        $request = ToolRequest::find($id);
        $request->status = 'Rejected';
        $request->save();
        // Update juga tool_fixs jika perlu
        SentToolReport::where('id', $request->sent_tool_id)->update([
            'status' => 'Rejected',
        ]);
        return redirect()->route('operator.tool.index');
    }
    public function operator_export(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            return Excel::download(new ToolExport($start, $end), 'tool_filtered.xlsx');
        } else {
            return Excel::download(new ToolExport(null, null), 'tool_all.xlsx');
        }
    }
    public function operator_exportPdf(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            $tool_fixs = SentToolReport::whereBetween('tanggal_pemeriksaan', [$start, $end])->get();
        } else {
            $tool_fixs = SentToolReport::all();
        }

        $pdf = Pdf::loadView('operator.tool.pdf', compact('tool_fixs'))
            ->setPaper('a4', 'landscape');;

        return $pdf->download('tool.pdf');
    }
}
