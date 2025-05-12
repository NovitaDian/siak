<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\Perusahaan;
use Illuminate\Support\Facades\Auth;
use App\Models\NonCompliant;
use App\Models\NonCompliantRequest;
use App\Models\SentPpe;
use Illuminate\Http\Request;

class NonCompliantController extends Controller
{
    // Menampilkan daftar NonCompliant
    public function index()
    {
        $nonCompliants = NonCompliant::with('ppeFix')->get();
        $requests = NonCompliantRequest::all();
        return view('adminsystem.non_compliant.index', compact('nonCompliants', 'requests'));
    }

    // Menampilkan form untuk membuat NonCompliant baru
    public function create($id)
    {
        $ppeFix = SentPpe::findOrFail($id);
        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();

        return view('adminsystem.non_compliant.create', compact('ppeFix', 'perusahaans', 'bagians'));
    }
    public function getBagian($perusahaan_name)
    {
        $bagians = Bagian::where('perusahaan_name', $perusahaan_name)->get();
        return response()->json($bagians);
    }

    // Menyimpan data NonCompliant baru
    public function store(Request $request)
    {
        $request->validate([
            'id_ppe' => 'required|integer',
            'tipe_observasi' => 'required|string',
            'nama_pelanggar' => 'required|string',
            'perusahaan' => 'required|string',
            'nama_bagian' => 'required|string',
            'nama_hse_inspector' => 'required|string',
            'shift_kerja' => 'required|string',
            'jam_pengawasan' => 'required|string',
            'zona_pengawasan' => 'required|string',
            'lokasi_observasi' => 'required|string',
        ]);
    
        NonCompliant::create([
            'id_ppe' => $request->id_ppe,
            'nama_hse_inspector' => $request->nama_hse_inspector,
            'shift_kerja' => $request->shift_kerja,
            'jam_pengawasan' => $request->jam_pengawasan,
            'zona_pengawasan' => $request->zona_pengawasan,
            'lokasi_observasi' => $request->lokasi_observasi,
            'tipe_observasi' => $request->tipe_observasi,
            'nama_pelanggar' => $request->nama_pelanggar,
            'perusahaan' => $request->perusahaan,
            'nama_bagian' => $request->nama_bagian,
            'deskripsi_ketidaksesuaian' => $request->deskripsi_ketidaksesuaian,
            'tindakan' => $request->tindakan,
            'writer' => Auth::user()->name,
        ]);
    
        return redirect()->route('adminsystem.ppe.index')->with('success', 'Data berhasil disimpan.');
    }
    
    
    
    



    // Menampilkan form untuk mengedit NonCompliant
    public function edit($id)
    {
        $ppeFix = SentPpe::findOrFail($id); 
        $nonCompliant = NonCompliant::where('id_ppe', $ppeFix->id)->first(); // Get the first matching record, assuming one exists.
        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();
        return view('adminsystem.non_compliant.edit', compact('nonCompliant', 'ppeFix','perusahaans','bagians'));
    }

    // Mengupdate data NonCompliant
    public function update(Request $request, $id)
{
    $request->validate([
        'id_ppe' => 'required|integer',
        'tipe_observasi' => 'required|string',
        'nama_pelanggar' => 'required|string',
        'perusahaan' => 'required|string',
        'nama_bagian' => 'required|string',
        'nama_hse_inspector' => 'required|string',
        'shift_kerja' => 'required|string',
        'jam_pengawasan' => 'required|string',
        'zona_pengawasan' => 'required|string',
        'lokasi_observasi' => 'required|string',
    ]);

    $nonCompliant = NonCompliant::findOrFail($id);

    $nonCompliant->update([
        'id_ppe' => $request->id_ppe,
        'nama_hse_inspector' => $request->nama_hse_inspector,
        'shift_kerja' => $request->shift_kerja,
        'jam_pengawasan' => $request->jam_pengawasan,
        'zona_pengawasan' => $request->zona_pengawasan,
        'lokasi_observasi' => $request->lokasi_observasi,
        'tipe_observasi' => $request->tipe_observasi,
        'nama_pelanggar' => $request->nama_pelanggar,
        'perusahaan' => $request->perusahaan,
        'nama_bagian' => $request->nama_bagian,
        'deskripsi_ketidaksesuaian' => $request->deskripsi_ketidaksesuaian,
        'tindakan' => $request->tindakan,
        'writer' => Auth::user()->name,
    ]);

    return redirect()->route('adminsystem.ppe.index')->with('success', 'Data berhasil diperbarui.');
}


    // Menghapus NonCompliant
    public function destroy($id)
    {
        $nonCompliant = NonCompliant::findOrFail($id);
        $nonCompliant->delete();

        return redirect()->route('adminsystem.non_compliant.index')->with('success', 'Non-Compliant deleted successfully!');
    }
    public function storeRequest(Request $request)
    {
        // Validate input
        $request->validate([
            'sent_daily_id' => 'required|exists:daily_fix,id', // Validate that the sent_daily_id exists in the daily_fix table
            'type' => 'required|string', // Ensure 'type' is required and is a string
            'reason' => 'required|string', // Ensure 'reason' is required and is a string
        ]);

        // Save request to the daily_request table
        NonCompliantRequest::create([
            'sent_daily_id' => $request->sent_daily_id, // Reference to the daily_fix record
            'type' => $request->type, // Request type
            'reason' => $request->reason, // Request reason
            'nama_pengirim' => Auth::user()->name, // The name of the user sending the request
        ]);

        // Return JSON response with a 201 status code (Created)
        return response()->json([
            'success' => true,
            'message' => 'Request submitted successfully.'
        ], 201);
    }

    public function approve($id)
    {
        $request = NonCompliantRequest::find($id);
        $request->status = 'Approved';
        $request->save();

        return response()->json(['success' => true]);
    }

    public function reject($id)
    {
        $request = NonCompliantRequest::find($id);
        $request->status = 'Rejected';
        $request->save();

        return response()->json(['success' => true]);
    }
}
