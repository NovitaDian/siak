<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Daily;
use App\Models\HseInspector;
use App\Models\ToolReport;
use App\Models\SentToolReport;
use App\Models\ToolRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ToolController extends Controller
{

    public function index()
    {

        $user = Auth::user();
        $tools = ToolReport::with('alat')
            ->where('writer', $user->name)
            ->get();
        $tool_fixs = SentToolReport::with('alat')->get();
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
        ]);

        $alat = Alat::findOrFail($request->alat_id);
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        ToolReport::create([
            'alat_id' => $alat->id,
            'nama_alat' => $alat->nama_alat,
            'hse_inspector_id' => $inspector->id,
            'hse_inspector' => $inspector->name,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'status_pemeriksaan' => $request->status_pemeriksaan,
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
        ]);

        $toolReport = ToolReport::findOrFail($id);

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
        ]);

        $tool_fixs = SentToolReport::findOrFail($id);
        $alat = Alat::findOrFail($request->alat_id);
        $inspector = HseInspector::findOrFail($request->hse_inspector_id);

        $tool_fixs->update([
            'alat_id' => $alat->id,
            'nama_alat' => $alat->nama_alat,
            'hse_inspector_id' => $inspector->id,
            'hse_inspector' => $inspector->name,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'status_pemeriksaan' => $request->status_pemeriksaan,
            'status' => 'Nothing',
            'writer' => Auth::user()->name,
        ]);
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
        $tools = ToolReport::find($id);
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

    public function storeRequest(Request $request)
    {
        // Validate input
        $request->validate([
            'sent_tool_id' => 'required|exists:tool_inspections_fix,id', // Perhatikan penulisan plural
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        // Simpan ke tabel request
        ToolRequest::create([
            'sent_tool_id' => $request->sent_tool_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => Auth::user()->name,
            'status' => 'Pending',
        ]);

        // Update status tool_inspections_fixs
        SentToolReport::where('id', $request->sent_tool_id)->update([
            'status' => 'Pending',
        ]);

        // Cek jika AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Request berhasil dikirim dan status diperbarui.',
            ]);
        }

        return redirect()->route('adminsystem.tool.index')->with('success', 'Request berhasil dikirim.');
    }



    public function approve($id)
    {
        $request = ToolRequest::findOrFail($id);
        $request->status = 'Approved';
        $request->save();

        // Update juga tool_inspections_fixs jika perlu
        SentToolReport::where('id', $request->sent_tool_id)->update([
            'status' => 'Approved',
        ]);

        return response()->json(['success' => true]);
    }


    public function reject($id)
    {
        $request = ToolRequest::find($id);
        $request->status = 'Rejected';
        $request->save();
        // Update juga tool_inspections_fixs jika perlu
        SentToolReport::where('id', $request->sent_tool_id)->update([
            'status' => 'Rejected',
        ]);
        return response()->json(['success' => true]);
    }
    public function sent_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $tool_fixs = SentToolReport::findOrFail($id);
        $tool_fixs->delete();
        // Redirect dengan notifikasi
        return redirect()->route('adminsystem.tool.index')->with('notification', 'NCR berhasil dikirim!');
    }
}
