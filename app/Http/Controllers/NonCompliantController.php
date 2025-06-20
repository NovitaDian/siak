<?php

namespace App\Http\Controllers;

use App\Mail\NonCompliantRequestNotification;
use App\Models\Bagian;
use App\Models\Perusahaan;
use Illuminate\Support\Facades\Auth;
use App\Models\NonCompliant;
use App\Models\NonCompliantRequest;
use App\Models\SentPpe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class NonCompliantController extends Controller
{
    // Menampilkan daftar NonCompliant
    public function index()
    {
        $nonCompliants = NonCompliant::with('ppeFix')->get();
        $requests = NonCompliantRequest::all();
        return view('adminsystem.ppe.show', compact('nonCompliants', 'requests'));
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
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
            'zona_pengawasan' => 'required|string',
            'lokasi_observasi' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('foto'); // jangan langsung include file upload
        $data['user_id'] = Auth::user()->id;
        $data['writer'] = Auth::user()->name;

        // Upload foto ke folder storage/app/public/pelanggar
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pelanggar', 'public'); // path: pelanggar/nama.jpg
            $data['foto'] = $fotoPath;
        }

        NonCompliant::create($data);

        return redirect()->route('adminsystem.ppe.show', $data['id_ppe'])
            ->with('success', 'Pelanggar berhasil ditambahkan!');
    }

    // Menampilkan form untuk mengedit NonCompliant
    public function edit($id)
    {
        $nonCompliant = NonCompliant::findOrFail($id);
        $ppeFix = SentPpe::findOrFail($nonCompliant->id_ppe);
        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();
        return view('adminsystem.non_compliant.edit', compact('nonCompliant', 'ppeFix', 'perusahaans', 'bagians'));
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
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
            'zona_pengawasan' => 'required|string',
            'lokasi_observasi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $nonCompliant = NonCompliant::findOrFail($id);
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['writer'] = Auth::user()->name;

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($nonCompliant->foto && Storage::disk('public')->exists($nonCompliant->foto)) {
                Storage::disk('public')->delete($nonCompliant->foto);
            }

            // Simpan foto baru
            $fotoPath = $request->file('foto')->store('pelanggar/foto', 'public');
            $data['foto'] = $fotoPath;
        }
        $data['status'] = 'Nothing';
        $nonCompliant->update($data);

        return redirect()->route('adminsystem.ppe.show', $data['id_ppe'])
            ->with('success', 'Data pelanggar berhasil diperbarui!');
    }
    // Menghapus NonCompliant
    public function destroy($id)
    {
        $nonCompliant = NonCompliant::findOrFail($id);
        $nonCompliant->delete();

        return redirect()->route('adminsystem.ppe.show', $nonCompliant['id_ppe'])
            ->with('success', 'Data pelanggar berhasil diperbarui!');
    }
    public function storeRequest(Request $request)
    {
        // Validate input
        $request->validate([
            'sent_non_compliant_id' => 'required|exists:non_compliants,id', // Validate that the sent_non_compliant_id exists in the non_compliant_fix table
            'type' => 'required|string', // Ensure 'type' is required and is a string
            'reason' => 'required|string', // Ensure 'reason' is required and is a string
        ]);

        // Save request to the non_compliant_request table
        NonCompliantRequest::create([
            'sent_non_compliant_id' => $request->sent_non_compliant_id, // Reference to the non_compliant_fix record
            'type' => $request->type, // Request type
            'reason' => $request->reason, // Request reason
            'nama_pengirim' => Auth::user()->name, // The name of the user sending the request
            'user_id' => Auth::user()->id,
        ]);
        NonCompliant::where('id', $request->sent_non_compliant_id)->update(['status' => 'Pending']);

        // Kirim email ke semua adminsystem
        $admins = User::where('role', 'adminsystem')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NonCompliantRequestNotification($request));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Request berhasil dikirim dan email telah dikirim ke admin.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Request berhasil dikirim dan email telah dikirim ke admin.'
        ], 201);
    }

    public function approve($id)
    {
        $request = NonCompliantRequest::find($id);
        $request->status = 'Approved';
        $request->save();
        NonCompliant::where('id', $request->sent_non_compliant_id)->update(['status' => 'Approved']);

        return response()->json(['success' => true]);
    }

    public function reject($id)
    {
        $request = NonCompliantRequest::find($id);
        $request->status = 'Rejected';
        $request->save();
        NonCompliant::where('id', $request->sent_non_compliant_id)->update(['status' => 'Rejected']);

        return response()->json(['success' => true]);
    }








    public function operator_index()
    {
        $nonCompliants = NonCompliant::with('ppeFix')->get();
        $requests = NonCompliantRequest::all();
        return view('adminsystem.non_compliant.index', compact('nonCompliants', 'requests'));
    }

    // Menampilkan form untuk membuat NonCompliant baru
    public function operator_create($id)
    {
        $ppeFix = SentPpe::findOrFail($id);
        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();

        return view('operator.non_compliant.create', compact('ppeFix', 'perusahaans', 'bagians'));
    }
    public function operator_getBagian($perusahaan_name)
    {
        $bagians = Bagian::where('perusahaan_name', $perusahaan_name)->get();
        return response()->json($bagians);
    }

    // Menyimpan data NonCompliant baru
    public function operator_store(Request $request)
    {

        $request->validate([
            'id_ppe' => 'required|integer',
            'tipe_observasi' => 'required|string',
            'nama_pelanggar' => 'required|string',
            'perusahaan' => 'required|string',
            'nama_bagian' => 'required|string',
            'nama_hse_inspector' => 'required|string',
            'shift_kerja' => 'required|string',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
            'zona_pengawasan' => 'required|string',
            'lokasi_observasi' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('foto'); // jangan langsung include file upload
        $data['user_id'] = Auth::user()->id;
        $data['writer'] = Auth::user()->name;

        // Upload foto ke folder storage/app/public/pelanggar
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pelanggar', 'public'); // path: pelanggar/nama.jpg
            $data['foto'] = $fotoPath;
        }

        NonCompliant::create($data);

        return redirect()->route('operator.ppe.show', $data['id_ppe'])
            ->with('success', 'Pelanggar berhasil ditambahkan!');
    }

    // Menampilkan form untuk mengedit NonCompliant
    public function operator_edit($id)
    {
        $ppeFix = SentPpe::findOrFail($id);
        $nonCompliant = NonCompliant::where('id_ppe', $ppeFix->id)->first(); // Get the first matching record, assuming one exists.
        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();
        return view('operator.non_compliant.edit', compact('nonCompliant', 'ppeFix', 'perusahaans', 'bagians'));
    }

    // Mengupdate data NonCompliant
    public function operator_update(Request $request, $id)
    {

        $request->validate([
            'id_ppe' => 'required|integer',
            'tipe_observasi' => 'required|string',
            'nama_pelanggar' => 'required|string',
            'perusahaan' => 'required|string',
            'nama_bagian' => 'required|string',
            'nama_hse_inspector' => 'required|string',
            'shift_kerja' => 'required|string',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
            'zona_pengawasan' => 'required|string',
            'lokasi_observasi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $nonCompliant = NonCompliant::findOrFail($id);
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['writer'] = Auth::user()->name;

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($nonCompliant->foto && Storage::disk('public')->exists($nonCompliant->foto)) {
                Storage::disk('public')->delete($nonCompliant->foto);
            }

            // Simpan foto baru
            $fotoPath = $request->file('foto')->store('pelanggar/foto', 'public');
            $data['foto'] = $fotoPath;
        }
        $data['status'] = 'Nothing';
        $nonCompliant->update($data);

        return redirect()->route('operator.ppe.show', $data['id_ppe'])
            ->with('success', 'Data pelanggar berhasil diperbarui!');
    }

    // Menghapus NonCompliant
    public function operator_destroy($id)
    {
        $nonCompliant = NonCompliant::findOrFail($id);
        $nonCompliant->delete();

        return redirect()->route('operator.ppe.show', $nonCompliant['id_ppe'])
            ->with('success', 'Data pelanggar berhasil diperbarui!');
    }
    public function operator_storeRequest(Request $request)
    {
        // Validate input
        $request->validate([
            'sent_non_compliant_id' => 'required|exists:non_compliants,id', // Validate that the sent_non_compliant_id exists in the non_compliant_fix table
            'type' => 'required|string', // Ensure 'type' is required and is a string
            'reason' => 'required|string', // Ensure 'reason' is required and is a string
        ]);

        // Save request to the non_compliant_request table
        NonCompliantRequest::create([
            'sent_non_compliant_id' => $request->sent_non_compliant_id, // Reference to the non_compliant_fix record
            'type' => $request->type, // Request type
            'reason' => $request->reason, // Request reason
            'nama_pengirim' => Auth::user()->name, // The name of the user sending the request
            'user_id' => Auth::user()->id,
        ]);
        NonCompliant::where('id', $request->sent_non_compliant_id)->update(['status' => 'Pending']);

        // Return JSON response with a 201 status code (Created)
        return response()->json([
            'success' => true,
            'message' => 'Request submitted successfully.'
        ], 201);
    }

    public function operator_approve($id)
    {
        $request = NonCompliantRequest::find($id);
        $request->status = 'Approved';
        $request->save();
        NonCompliant::where('id', $request->sent_non_compliant_id)->update(['status' => 'Approved']);

        return response()->json(['success' => true]);
    }

    public function operator_reject($id)
    {
        $request = NonCompliantRequest::find($id);
        $request->status = 'Rejected';
        $request->save();
        NonCompliant::where('id', $request->sent_non_compliant_id)->update(['status' => 'Rejected']);

        return response()->json(['success' => true]);
    }
}
