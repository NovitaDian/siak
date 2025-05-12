<?php

namespace App\Http\Controllers;

use App\Mail\NcrRequestNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\Bagian;
use App\Models\Ncr;
use App\Models\NcrRequest;
use App\Models\Perusahaan;
use App\Models\SentNcr;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class NcrController extends Controller
{
    // Menampilkan semua data NCR
    public function index()
    {
        $requests = NcrRequest::all();
        $user = Auth::user();
        $ncrs = Ncr::where('writer', $user->name)->get();

        // Ambil semua NCR dan hitung warna berdasarkan durasi
        $ncr_fixs = SentNcr::all()->map(function ($item) {
            $warna = 'bg-gray-200 text-black'; // default
            if ($item->durasi_ncr && strpos($item->durasi_ncr, '-') !== false) {
                $parts = explode('-', $item->durasi_ncr); // 'YYYY-MM-DD HH:MM:SS'
                if (count($parts) >= 2) {
                    $tahun = (int)$parts[0];
                    $bulanHari = explode(' ', $parts[1]);
                    $bulan = isset($bulanHari[0]) ? (int)$bulanHari[0] : 0;

                    $totalBulan = $tahun * 12 + $bulan;

                    if ($totalBulan < 3) {
                        $warna = 'bg-green-500 text-white';
                    } elseif ($totalBulan >= 3 && $totalBulan < 6) {
                        $warna = 'bg-yellow-400 text-black';
                    } elseif ($totalBulan >= 6 && $totalBulan < 9) {
                        $warna = 'bg-orange-400 text-white';
                    } elseif ($totalBulan >= 9 && $totalBulan < 12) {
                        $warna = 'bg-red-500 text-white';
                    } else {
                        $warna = 'bg-black text-white';
                    }
                }
            }
            $item->warna_durasi = $warna;
            return $item;
        });

        return view('adminsystem.ncr.index', compact('ncrs', 'ncr_fixs', 'requests'));
    }

    // Menampilkan detail data NCR berdasarkan ID
    public function show($id)
    {
        $ncr = Ncr::findOrFail($id);

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
        $data['writer'] = Auth::user()->name; // atau auth()->user()->id tergantung kebutuhan

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
        // Validate input
        $request->validate([
            'sent_ncr_id' => 'required|exists:ncr_fix,id',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        // Save request to the ncr_request table
        NcrRequest::create([
            'sent_ncr_id' => $request->sent_ncr_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => Auth::user()->name,
        ]);
         // Kirim email ke semua adminsystem
        $admins = User::where('role', 'adminsystem')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NcrRequestNotification($request));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Request berhasil dikirim dan email telah dikirim ke admin.',
            ]);
        }


        // Return JSON response
        return response()->json(['success' => true, 'message' => 'Request submitted successfully.']);
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

    public function getBagian($perusahaan_name)
    {
        $bagians = Bagian::where('perusahaan_name', $perusahaan_name)->get();
        return response()->json($bagians);
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
            'nama_hs_officer_1' => 'required|string|max:255',
            'nama_hs_officer_2' => 'required|string|max:255',
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
        ]);

        return redirect()->route('adminsystem.ncr.index')->with('success', 'NCR berhasil di-close!');
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
