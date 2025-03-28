<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\Ncr;
use App\Models\NcrRequest;
use App\Models\Perusahaan;
use App\Models\SentNcr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NcrController extends Controller
{
    // Menampilkan semua data NCR
    public function index()
    {
        $requests = NcrRequest::all();
        $ncr_fixs = SentNcr::all();
        $user = auth()->user(); // Get the currently authenticated user
        $ncrs = Ncr::where('writer', $user->name)->get(); // Filter NCRs by writer
        return view('adminsystem.ncr.index', compact('ncrs', 'ncr_fixs', 'requests'));
    }

    // Menampilkan detail data NCR berdasarkan ID
    public function show($id)
    {
        $ncr = Ncr::find($id);

        if (!$ncr) {
            return response()->json(['message' => 'Data NCR tidak ditemukan'], 404);
        }
        if (is_string($ncr->kategori_ketidaksesuaian)) { // Jika masih string
            $ncr->kategori_ketidaksesuaian = explode(',', $ncr->kategori_ketidaksesuaian); // Ubah ke array
        }
        $tanggalHariIni = Carbon::now()->format('d F Y'); // Format: 11 November 2023


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
            'nama_hs_officer_1' => 'required|string|max:255',
            'nama_hs_officer_2' => 'required|string|max:255',
            'tanggal_audit' => 'required|date',
            'nama_auditee' => 'required|string|max:255',
            'perusahaan' => 'required|string|max:255',
            'bagian' => 'nullable|string|max:255',
            'element_referensi_ncr' => 'required|string|max:255',
            'kategori_ketidaksesuaian' => 'required|string|max:255',
            'deskripsi_ketidaksesuaian' => 'required|string',
        ]);

        // Menambahkan kolom 'writer' dengan user yang sedang login
        $data = $request->all();
        $data['writer'] = auth()->user()->name; // atau auth()->user()->id tergantung kebutuhan

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
            'nama_hs_officer_1' => 'required|string|max:255',
            'nama_hs_officer_2' => 'required|string|max:255',
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
            'nama_hs_officer_1' => 'required|string|max:255',
            'nama_hs_officer_2' => 'required|string|max:255',
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
        // Ambil data PPE berdasarkan ID
        $ncr = Ncr::findOrFail($id);

        // Menyiapkan data untuk dimasukkan ke ncr_fix, pastikan data diubah menjadi array
        $dataToInsert = $ncr->toArray();

        // Memastikan created_at dan updated_at ditambahkan (jika tabel ncr_fix memerlukannya)
        $dataToInsert['created_at'] = $ncr->created_at;
        $dataToInsert['updated_at'] = $ncr->updated_at;

        // Cek apakah data sudah ada di ncr_fix berdasarkan ID atau kolom unik lainnya
        $exists = DB::table('ncr_fix')->where('id', $ncr->id)->exists();

        if (!$exists) {
            // Insert data hanya jika belum ada
            DB::table('ncr_fix')->insert($dataToInsert);
        }

        // Hapus data ncr asli
        $ncr->delete();

        // Redirect dengan notifikasi
        return redirect()->route('adminsystem.ncr.index')->with('notification', 'NCR berhasil dikirim!');
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
        // Validasi input
        $request->validate([
            'sent_ncr_id' => 'required|exists:ncr_fix,id', // Pastikan NCR ID ada
            'type' => 'required|in:edit,delete', // Validasi jenis permintaan
            'reason' => 'required|string', // Validasi alasan
        ]);

        // Simpan request ke dalam tabel ncr_request
        $ncrRequest = NcrRequest::create([
            'sent_ncr_id' => $request->sent_ncr_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => auth()->user()->name, // Menambahkan nama pengirim
        ]);

        // Redirect dengan pesan sukses
        return view('adminsystem.ncr.index');
    }
    public function approve($id)
    {
        $request = NcrRequest::find($id);
        $request->status = 'Approved';
        $request->save();

        return response()->json(['success' => true]);
    }

    public function reject($id)
    {
        $request = NcrRequest::find($id);
        $request->status = 'Rejected';
        $request->save();

        return response()->json(['success' => true]);
    }




    // MASTER
    public function master()
    {
        $pers = Perusahaan::all();
        $bagians = Bagian::all();
        return view('adminsystem.ncr.master', compact('pers', 'bagians'));
    }












    //PERUSAHAAN
    // Menampilkan perusahaan berdasarkan ID
    public function Perusahaanshow($id)
    {
        $perusahaan = Perusahaan::find($id);
        if ($perusahaan) {
            return response()->json($perusahaan);
        } else {
            return response()->json(data: ['message' => 'Perusahaan tidak ditemukan']);
        }
    }
    public function Perusahaancreate()
    {
        $pers = Perusahaan::all();

        // Mengirim data ke view
        return view('adminsystem.ncr.perusahaan.create', compact('pers'));
    }
    public function Perusahaanedit($id)
    {
        $pers = Perusahaan::findOrFail($id);

        // Mengirim data ke view
        return view('adminsystem.ncr.perusahaan.create', compact('pers'));
    }
    // Menambahkan perusahaan baru
    public function Perusahaanstore(Request $request)
    {
        $request->validate([
            'perusahaan_code' => 'required|unique:perusahaan',
            'perusahaan_name' => 'required',
            'city' => 'required',
            'street' => 'required',
        ]);

        $pers = Perusahaan::create([
            'perusahaan_code' => $request->perusahaan_code,
            'perusahaan_name' => $request->perusahaan_name,
            'city' => $request->city,
            'street' => $request->street,
        ]);

        return view('adminsystem.ncr.master');
    }

    // Mengupdate data perusahaan
    public function Perusahaanupdate(Request $request, $id)
    {
        $perusahaan = Perusahaan::find($id);
        if (!$perusahaan) {
            return response()->json(['message' => 'Perusahaan tidak ditemukan'], 404);
        }

        $perusahaan->update([
            'perusahaan_code' => $request->perusahaan,
            'perusahaan_name' => $request->perusahaan,
            'city' => $request->city,
            'street' => $request->street,
        ]);

        return view('adminsystem.ncr.master');
    }

    // Menghapus perusahaan
    public function Perusahaandestroy($id)
    {
        $perusahaan = Perusahaan::find($id);
        if (!$perusahaan) {
            return response()->json(['message' => 'Perusahaan tidak ditemukan'], 404);
        }

        $perusahaan->delete();
        return redirect()->route('adminsystem.ncr.master')->with('notification', 'Perusahaan berhasil dikirim!');
    }
    // Di dalam Controller
    public function getBagian($perusahaan_name)
    {
        $bagians = Bagian::where('perusahaan_name', $perusahaan_name)->get();
        return response()->json($bagians);
    }
    public function bagian_create()
    {
        $perusahaans = Perusahaan::all();

        // Mengirim data ke view
        return view('adminsystem.ncr.bagian.create', compact('perusahaans'));
    }
    public function bagian_store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'perusahaan_code' => 'required|exists:perusahaan,perusahaan_code',
            'nama_bagian' => 'required|string|max:255',
        ]);

        // Get perusahaan_name based on selected perusahaan_code
        $perusahaan = Perusahaan::where('perusahaan_code', $request->perusahaan_code)->first();

        if ($perusahaan) {
            // Create a new Bagian record with the selected perusahaan_name
            Bagian::create([
                'perusahaan_code' => $request->perusahaan_code,
                'perusahaan_name' => $perusahaan->perusahaan_name,  // Auto-fill the perusahaan_name
                'nama_bagian' => $request->nama_bagian,
            ]);

            // Redirect with a success message
            return redirect()->route('adminsystem.ncr.master')->with('success', 'Bagian created successfully.');
        }

        // In case of failure, redirect with error message
        return redirect()->back()->with('error', 'Perusahaan not found.');
    }
    public function bagian_edit($id)
    {
        $bagian = Bagian::findOrFail($id);
        $perusahaans = Perusahaan::all();

        // Mengirim data ke view
        return view('adminsystem.ncr.bagian.edit', compact('perusahaans','bagian'));
    }

    public function bagian_update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'perusahaan_code' => 'required|exists:perusahaan,perusahaan_code',
            'nama_bagian' => 'required|string|max:255',
        ]);

        // Find the existing Bagian record
        $bagian = Bagian::findOrFail($id);

        // Retrieve the Perusahaan name based on the selected perusahaan_code
        $perusahaan = Perusahaan::where('perusahaan_code', $request->perusahaan_code)->first();

        // Update the Bagian record with new values
        $bagian->perusahaan_code = $request->perusahaan_code;
        $bagian->perusahaan_name = $perusahaan->perusahaan_name; // Set the perusahaan_name from the selected perusahaan_code
        $bagian->nama_bagian = $request->nama_bagian;
        $bagian->save(); // Save the changes

        // Redirect back with a success message
        return redirect()->route('adminsystem.ncr.master')->with('success', 'Data Bagian updated successfully!');
    }
    public function bagian_destroy($id)
    {
        $bagian = Bagian::find($id);
        if (!$bagian) {
            return response()->json(['message' => 'Bagian tidak ditemukan'], 404);
        }

        $bagian->delete();
        return redirect()->route('adminsystem.ncr.master')->with('notification', 'Bagian berhasil dikirim!');
    }














    // OPERATOR
    public function operator_index()
    {
        $user = auth()->user(); // Get the currently authenticated user
        $requests = NcrRequest::all();
        $ncrs = Ncr::where('writer', $user->name)->get(); // Filter NCRs by writer
        $ncr_fixs = SentNcr::where('writer', $user->name)->get(); // Filter Sent NCRs by writer

        return view('operator.ncr.index', compact('requests', 'ncrs', 'ncr_fixs'));
    }
    public function operator_create()
    {
        // Retrieve all companies and sections
        $pers = Perusahaan::all();
        $bagians = Bagian::all();

        // Return the view for creating a new NCR
        return view('operator.ncr.report', compact('perusahaans', 'bagians'));
    }
    // Menampilkan detail data NCR berdasarkan ID
    public function operator_show($id)
    {
        $ncr = Ncr::find($id);

        if (!$ncr) {
            return response()->json(['message' => 'Data NCR tidak ditemukan'], 404);
        }
        if (is_string($ncr->kategori_ketidaksesuaian)) { // Jika masih string
            $ncr->kategori_ketidaksesuaian = explode(',', $ncr->kategori_ketidaksesuaian); // Ubah ke array
        }
        $tanggalHariIni = Carbon::now()->format('d F Y'); // Format: 11 November 2023


        return view('operator.ncr.show', compact('ncr', 'tanggalHariIni'));
    }

    public function operator_edit($id)
    {
        // Retrieve the NCR record by ID
        $ncr = Ncr::findOrFail($id);

        // Retrieve all companies and sections
        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();

        // Pass the data to the edit view
        return view('operator.ncr.edit', compact('ncr', 'perusahaans', 'bagians'));
    }
    public function operator_sent_edit($id)
    {
        // Retrieve the NCR record by ID
        $ncr_fixs = SentNcr::findOrFail($id);

        // Retrieve all companies and sections
        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();

        // Pass the data to the edit view
        return view('operator.ncr.sent_edit', compact('ncr_fixs', 'perusahaans', 'bagians'));
    }

    // Menambahkan data NCR baru
    public function operator_store(Request $request)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:255',
            'nama_hs_officer_1' => 'required|string|max:255',
            'nama_hs_officer_2' => 'required|string|max:255',
            'tanggal_audit' => 'required|date',
            'nama_auditee' => 'required|string|max:255',
            'perusahaan' => 'required|string|max:255',
            'bagian' => 'nullable|string|max:255',
            'element_referensi_ncr' => 'required|string|max:255',
            'kategori_ketidaksesuaian' => 'required|string|max:255',
            'deskripsi_ketidaksesuaian' => 'required|string',
        ]);

        // Menambahkan kolom 'writer' dengan user yang sedang login
        $data = $request->all();
        $data['writer'] = auth()->user()->name; // atau auth()->user()->id tergantung kebutuhan

        Ncr::create($data);

        return redirect()->route('operator.ncr.index')->with('success', 'Data berhasil disimpan!');
    }

    // Memperbarui data NCR yang ada
    public function operator_update(Request $request, $id)
    {
        $ncr = Ncr::find($id);

        if (!$ncr) {
            return response()->json(['message' => 'Data NCR tidak ditemukan'], 404);
        }

        // Validasi data yang diterima
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:255',
            'nama_hs_officer_1' => 'required|string|max:255',
            'nama_hs_officer_2' => 'required|string|max:255',
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

        return redirect()->route('operator.ncr.index')->with('success', 'Data berhasil diupdate!');
    }
    public function operator_sent_update(Request $request, $id)
    {
        $ncr_fixs = SentNcr::find($id);

        if (!$ncr_fixs) {
            return response()->json(['message' => 'Data NCR tidak ditemukan'], 404);
        }

        // Validasi data yang diterima
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:255',
            'nama_hs_officer_1' => 'required|string|max:255',
            'nama_hs_officer_2' => 'required|string|max:255',
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

        return redirect()->route('operator.ncr.index')->with('success', 'Data berhasil diupdate!');
    }
    // Menghapus data NCR berdasarkan ID
    public function operator_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $ncr = Ncr::findOrFail($id);

        // Menyiapkan data untuk dimasukkan ke ncr_fix, pastikan data diubah menjadi array
        $dataToInsert = $ncr->toArray();

        // Memastikan created_at dan updated_at ditambahkan (jika tabel ncr_fix memerlukannya)
        $dataToInsert['created_at'] = $ncr->created_at;
        $dataToInsert['updated_at'] = $ncr->updated_at;

        // Cek apakah data sudah ada di ncr_fix berdasarkan ID atau kolom unik lainnya
        $exists = DB::table('ncr_fix')->where('id', $ncr->id)->exists();

        if (!$exists) {
            // Insert data hanya jika belum ada
            DB::table('ncr_fix')->insert($dataToInsert);
        }

        // Hapus data ncr asli
        $ncr->delete();

        // Redirect dengan notifikasi
        return redirect()->route('operator.ncr.index')->with('notification', 'NCR berhasil dikirim!');
    }
    public function operator_sent_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $ncr_fixs = SentNcr::findOrFail($id);
        $ncr_fixs->delete();
        // Redirect dengan notifikasi
        return redirect()->route('operator.ncr.index')->with('notification', 'NCR berhasil dikirim!');
    }
    public function operator_Perusahaanshow($id)
    {
        $perusahaan = Perusahaan::find($id);
        if ($perusahaan) {
            return response()->json($perusahaan);
        } else {
            return response()->json(data: ['message' => 'Perusahaan tidak ditemukan']);
        }
    }
    public function operator_getBagian($perusahaan_name)
    {
        $bagians = Bagian::where('perusahaan_name', $perusahaan_name)->get();
        return response()->json($bagians);
    }
    public function operator_storeRequest(Request $request)
    {
        $request->validate([
            'sent_ncr_id' => 'required|exists:ncr_fix,id', // Ensure the sent NCR ID exists
            'type' => 'required|in:Edit,Delete', // Validate the type
            'reason' => 'required|string|max:255', // Validate the reason
        ]);

        // Create a new request record or handle the logic as needed
        $ncrRequest = new NcrRequest();
        $ncrRequest->sent_ncr_id = $request->sent_ncr_id;
        $ncrRequest->type = $request->type;
        $ncrRequest->reason = $request->reason;
        $ncrRequest->nama_pengirim = auth()->user()->name; // Assuming you want to store the writer's name
        $ncrRequest->save();

        return redirect()->back()->with('success', 'Request submitted successfully.');
    }
    public function operator_approve($id)
    {
        $request = NcrRequest::find($id);
        $request->status = 'Approved';
        $request->save();

        return response()->json(['success' => true]);
    }

    public function operator_reject($id)
    {
        $request = NcrRequest::find($id);
        $request->status = 'Rejected';
        $request->save();

        return response()->json(['success' => true]);
    }
}
