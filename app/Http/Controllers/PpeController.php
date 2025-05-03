<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\HseInspector;
use Illuminate\Http\Request;
use App\Models\Ppe;
use App\Models\PpeRequest;
use App\Models\SentPpe;
use Illuminate\Support\Facades\DB;

class PpeController extends Controller
{
    // Menampilkan semua data observasi
    public function index()
    {
        $user = Auth::user(); // Get the currently authenticated user
        $ppes = Ppe::where('writer', $user->name)->get();
        $ppe_fixs = SentPpe::all();
        $requests = PpeRequest::all();
        return view('adminsystem.ppe.index', compact('ppes', 'ppe_fixs', 'requests'));
    }

    // Menampilkan form untuk membuat data baru
    public function create()
    {
        $officers = HseInspector::all();
        return view('adminsystem.ppe.report', compact('officers'));
    }

    // Menyimpan data baru ke database
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'nama_hse_inspector' => 'required|string|max:100',
            'jam_pengawasan' => 'required',
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

        // Add the 'writer' field to the validated data
        $validatedData = $request->all();
        $validatedData['writer'] = Auth::user()->name;
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

        // Set status_ppe
        $validatedData['status_ppe'] = ($total_tidak_patuh == 0) ? 'Compliant' : 'Non-Compliant';
        // Create a new record in the Ppe model
        Ppe::create($validatedData);

        // Redirect with a success message
        return redirect()->route('adminsystem.ppe.index')->with('success', 'Data berhasil disimpan!');
    }


    // Menampilkan detail data
    public function show($id)
    {
        $ppe = Ppe::findOrFail($id);
        return view('ppe.show', compact('ppe'));
    }

    // Menampilkan form edit data
    public function edit($id)
    {
        $ppe = Ppe::findOrFail($id);
        return view('ppe.edit', compact('ppe'));
    }

    // Mengupdate data ke database
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'nama_hse_inspector' => 'required|string|max:100',
            'jam_pengawasan' => 'required',
            'zona_pengawasan' => 'required|string|max:100',
            'lokasi_observasi' => 'required|string|max:100',
            'jumlah_patuh_apd_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_helm_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_sepatu_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => 'required|integer|min:0',
            'keterangan_tidak_patuh_karyawan' => 'nullable|string|max:255',
            'jumlah_patuh_apd_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_helm_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_sepatu_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => 'required|integer|min:0',
            'keterangan_tidak_patuh' => 'nullable|string|max:255',
        ]);
        $validatedData = $request->all();

        // Hitung total ketidakpatuhan
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
    
        // Set status_ppe
        $validatedData['status_ppe'] = ($total_tidak_patuh == 0) ? 'Compliant' : 'Non-Compliant';
    
        // Update data
        $ppe = Ppe::findOrFail($id);
        $ppe->update($validatedData);
        return redirect()->route('adminsystem.ppe.index')->with('success', 'Data berhasil diupdate!');
    }

    // Menghapus data dari database
    public function destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $ppe = Ppe::findOrFail($id);

        // Menyiapkan data untuk dimasukkan ke ppe_fix, pastikan data diubah menjadi array
        $dataToInsert = $ppe->toArray();

        // Memastikan created_at dan updated_at ditambahkan (jika tabel ppe_fix memerlukannya)
        $dataToInsert['created_at'] = $ppe->created_at;
        $dataToInsert['updated_at'] = $ppe->updated_at;

        // Cek apakah data sudah ada di ppe_fix berdasarkan ID atau kolom unik lainnya
        $exists = DB::table('ppe_fix')->where('id', $ppe->id)->exists();

        if (!$exists) {
            // Insert data hanya jika belum ada
            DB::table('ppe_fix')->insert($dataToInsert);
        }

        // Hapus data PPE asli
        $ppe->delete();

        // Redirect dengan notifikasi
        return redirect()->route('adminsystem.ppe.index')->with('notification', 'PPE berhasil dipindahkan ke ppe_fix!');
    }
    public function storeRequest(Request $request)
    {
        // Validate input
        $request->validate([
            'sent_ppe_id' => 'required|exists:ppe_fix,id',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        // Save request to the ppe_request table
        PpeRequest::create([
            'sent_ppe_id' => $request->sent_ppe_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => Auth::user()->name,
        ]);

        // Return JSON response
        return response()->json(['success' => true, 'message' => 'Request submitted successfully.']);
    }
    public function sent_edit($id)
    {
        // Retrieve the NCR record by ID
        $ppe_fixs = SentPpe::findOrFail($id);
        return view('adminsystem.ppe.sent_edit', compact('ppe_fixs'));
    }
    public function sent_update(Request $request, $id)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'nama_hse_inspector' => 'required|string|max:100',
            'jam_pengawasan' => 'required',
            'zona_pengawasan' => 'required|string|max:100',
            'lokasi_observasi' => 'required|string|max:100',
            'jumlah_patuh_apd_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_helm_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_sepatu_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => 'required|integer|min:0',
            'keterangan_tidak_patuh_karyawan' => 'nullable|string|max:255',
            'jumlah_patuh_apd_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_helm_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_sepatu_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => 'required|integer|min:0',
            'keterangan_tidak_patuh' => 'nullable|string|max:255',
        ]);

        $ppe_fixs = Ppe::findOrFail($id);

        // Hitung total ketidakpatuhan
        $total_tidak_patuh =
            $request->jumlah_tidak_patuh_helm_karyawan +
            $request->jumlah_tidak_patuh_sepatu_karyawan +
            $request->jumlah_tidak_patuh_pelindung_mata_karyawan +
            $request->jumlah_tidak_patuh_safety_harness_karyawan +
            $request->jumlah_tidak_patuh_apd_lainnya_karyawan +
            $request->jumlah_tidak_patuh_helm_kontraktor +
            $request->jumlah_tidak_patuh_sepatu_kontraktor +
            $request->jumlah_tidak_patuh_pelindung_mata_kontraktor +
            $request->jumlah_tidak_patuh_safety_harness_kontraktor +
            $request->jumlah_tidak_patuh_apd_lainnya_kontraktor;

        // Tentukan status_ppe
        $status_ppe = ($total_tidak_patuh == 0) ? 'Compliant' : 'Non-Compliant';

        // Update semua data + status_ppe
        $data = $request->all();
        $data['status_ppe'] = $status_ppe;

        $ppe_fixs->update($data);

        return redirect()->route('adminsystem.ppe.index')->with('success', 'Data berhasil diupdate!');
    }

    public function sent_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $ppe_fixs = SentPpe::findOrFail($id);
        $ppe_fixs->delete();
        // Redirect dengan notifikasi
        return redirect()->route('adminsystem.ppe.index')->with('notification', 'NCR berhasil dikirim!');
    }
    public function approve($id)
    {
        $request = PpeRequest::find($id);
        $request->status = 'Approved';
        $request->save();

        return response()->json(['success' => true]);
    }

    public function reject($id)
    {
        $request = PpeRequest::find($id);
        $request->status = 'Rejected';
        $request->save();

        return response()->json(['success' => true]);
    }







    // OPERATOR
    public function operator_index()
    {
        $user = Auth::user(); // Get the currently authenticated user
        $ppes = Ppe::where('writer', $user->name)->get();
        $ppe_fixs = SentPpe::all();
        $requests = PpeRequest::all();
        return view('operator.ppe.index', compact('ppes', 'ppe_fixs', 'requests'));
    }

    // Menampilkan form untuk membuat data baru
    public function operator_create()
    {
        return view('operator.ppe.report');
    }

    // Menyimpan data baru ke database
    public function operator_store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'nama_hse_inspector' => 'required|string|max:100',
            'jam_pengawasan' => 'required',
            'zona_pengawasan' => 'required|string|max:100',
            'lokasi_observasi' => 'required|string|max:100',
            'jumlah_patuh_apd_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_helm_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_sepatu_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => 'required|integer|min:0',
            'keterangan_tidak_patuh_karyawan' => 'nullable|string|max:255',
            'jumlah_patuh_apd_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_helm_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_sepatu_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => 'required|integer|min:0',
            'keterangan_tidak_patuh' => 'nullable|string|max:255',
        ]);

        // Add the 'writer' field to the validated data
        $validatedData = $request->all();
        $validatedData['writer'] = Auth::user()->name;

        // Create a new record in the Ppe model
        Ppe::create($validatedData);

        // Redirect with a success message
        return redirect()->route('operator.ppe.index')->with('success', 'Data berhasil disimpan!');
    }


    // Menampilkan detail data
    public function operator_show($id)
    {
        $ppe = Ppe::findOrFail($id);
        return view('ppe.show', compact('ppe'));
    }

    // Menampilkan form edit data
    public function operator_edit($id)
    {
        $ppe = Ppe::findOrFail($id);
        return view('ppe.edit', compact('ppe'));
    }

    // Mengupdate data ke database
    public function operator_update(Request $request, $id)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'nama_hse_inspector' => 'required|string|max:100',
            'jam_pengawasan' => 'required',
            'zona_pengawasan' => 'required|string|max:100',
            'lokasi_observasi' => 'required|string|max:100',
            'jumlah_patuh_apd_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_helm_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_sepatu_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => 'required|integer|min:0',
            'keterangan_tidak_patuh_karyawan' => 'nullable|string|max:255',
            'jumlah_patuh_apd_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_helm_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_sepatu_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => 'required|integer|min:0',
            'keterangan_tidak_patuh' => 'nullable|string|max:255',
        ]);

        $ppe = Ppe::findOrFail($id);
        $ppe->update($request->all());

        return redirect()->route('operator.ppe.index')->with('success', 'Data berhasil diupdate!');
    }

    // Menghapus data dari database
    public function operator_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $ppe = Ppe::findOrFail($id);

        // Menyiapkan data untuk dimasukkan ke ppe_fix, pastikan data diubah menjadi array
        $dataToInsert = $ppe->toArray();

        // Memastikan created_at dan updated_at ditambahkan (jika tabel ppe_fix memerlukannya)
        $dataToInsert['created_at'] = $ppe->created_at;
        $dataToInsert['updated_at'] = $ppe->updated_at;

        // Cek apakah data sudah ada di ppe_fix berdasarkan ID atau kolom unik lainnya
        $exists = DB::table('ppe_fix')->where('id', $ppe->id)->exists();

        if (!$exists) {
            // Insert data hanya jika belum ada
            DB::table('ppe_fix')->insert($dataToInsert);
        }

        // Hapus data PPE asli
        $ppe->delete();

        // Redirect dengan notifikasi
        return redirect()->route('operator.ppe.index')->with('notification', 'PPE berhasil dipindahkan ke ppe_fix!');
    }
    public function operator_storeRequest(Request $request)
    {
        // Validate input
        $request->validate([
            'sent_ppe_id' => 'required|exists:ppe_fix,id',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        // Save request to the ppe_request table
        PpeRequest::create([
            'sent_ppe_id' => $request->sent_ppe_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => Auth::user()->name,
        ]);

        // Return JSON response
        return response()->json(['success' => true, 'message' => 'Request submitted successfully.']);
    }
    public function operator_sent_edit($id)
    {
        // Retrieve the NCR record by ID
        $ppe_fixs = SentPpe::findOrFail($id);
        return view('operator.ppe.sent_edit', compact('ppe_fixs'));
    }
    public function operator_sent_update(Request $request, $id)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'nama_hse_inspector' => 'required|string|max:100',
            'jam_pengawasan' => 'required',
            'zona_pengawasan' => 'required|string|max:100',
            'lokasi_observasi' => 'required|string|max:100',
            'jumlah_patuh_apd_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_helm_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_sepatu_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_karyawan' => 'required|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_karyawan' => 'required|integer|min:0',
            'keterangan_tidak_patuh_karyawan' => 'nullable|string|max:255',
            'jumlah_patuh_apd_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_helm_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_sepatu_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_pelindung_mata_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_safety_harness_kontraktor' => 'required|integer|min:0',
            'jumlah_tidak_patuh_apd_lainnya_kontraktor' => 'required|integer|min:0',
            'keterangan_tidak_patuh' => 'nullable|string|max:255',
        ]);

        $ppe_fixs = Ppe::findOrFail($id);
        $ppe_fixs->update($request->all());

        return redirect()->route('operator.ppe.index')->with('success', 'Data berhasil diupdate!');
    }
    public function operator_sent_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $ppe_fixs = SentPpe::findOrFail($id);
        $ppe_fixs->delete();
        // Redirect dengan notifikasi
        return redirect()->route('operator.ppe.index')->with('notification', 'NCR berhasil dikirim!');
    }
    public function operator_approve($id)
    {
        $request = PpeRequest::find($id);
        $request->status = 'Approved';
        $request->save();

        return response()->json(['success' => true]);
    }

    public function operator_reject($id)
    {
        $request = PpeRequest::find($id);
        $request->status = 'Rejected';
        $request->save();

        return response()->json(['success' => true]);
    }
}
