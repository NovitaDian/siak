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
use Illuminate\Support\Facades\Storage;

class NcrController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil data draft NCR oleh user login
        $ncrs = Ncr::where('user_id', $user->id)->get();
        $allRequests = NcrRequest::all();

        // Ambil semua request (Edit/Delete)
        $latestRequests = NcrRequest::orderByDesc('id')
            ->get()
            ->unique('sent_ncr_id');
        // Filter tanggal jika ada
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            $ncr_fixs = SentNcr::whereBetween('tanggal_shift_kerja', [$start, $end])
                ->orderBy('tanggal_shift_kerja', 'desc') // Terbaru di atas
                ->get();
        } else {
            $ncr_fixs = SentNcr::orderBy('tanggal_shift_kerja', 'desc') // Terbaru di atas
                ->get();
        }

        return view('adminsystem.ncr.index', compact('ncrs', 'ncr_fixs', 'latestRequests', 'allRequests'));
    }


    // Menampilkan detail data NCR berdasarkan ID
    public function show($id)
    {
        $ncr = Ncr::findOrFail($id);
        return view('adminsystem.ncr.draft_show', compact('ncr'));
    }
    public function sent_show($id)
    {
        $ncr = SentNcr::findOrFail($id);
        return view('adminsystem.ncr.show', compact('ncr'));
    }
    public function closed_show($id)
    {
        $ncr = SentNcr::findOrFail($id);
        return view('adminsystem.ncr.closed_show', compact('ncr'));
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
            'perusahaan_id' => 'nullable|exists:perusahaan,id',
            'nama_bagian' => 'nullable|string|max:255',
            'element_referensi_ncr' => 'required|string|max:255',
            'kategori_ketidaksesuaian' => 'required|string|max:255',
            'estimasi' => 'required|date',
            'tindak_lanjut' => 'required|string',
            'foto' => 'required|file|mimes:pdf,jpeg,png,jpg,gif|max:2048',
        ]);

        // Ambil semua input dan tambahkan user info
        $data = $request->except(['foto']);
        $data['user_id'] = Auth::id();


        // Upload Foto
        if ($request->hasFile('foto')) {
            $imageName = time() . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->move(public_path('storage/ncr'), $imageName);
            $data['foto'] = 'ncr/' . $imageName;
        }

        // Simpan ke database
        Ncr::create($data);

        return redirect()->route('adminsystem.ncr.index')->with('success', 'Data berhasil disimpan!');
    }

    // Memperbarui data NCR yang ada
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:255',
            'nama_hs_officer_1' => 'nullable|string|max:255',
            'nama_hs_officer_2' => 'nullable|string|max:255',
            'tanggal_audit' => 'required|date',
            'nama_auditee' => 'required|string|max:255',
            'perusahaan_id' => 'nullable|exists:perusahaan,id',
            'nama_bagian' => 'nullable|string|max:255',
            'element_referensi_ncr' => 'required|string|max:255',
            'kategori_ketidaksesuaian' => 'required|string|max:255',
            'estimasi' => 'required|date',
            'tindak_lanjut' => 'required|string',
            'foto' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:2048',
        ]);

        $ncr = Ncr::findOrFail($id);


        $data = $request->except(['foto']);

        $data['updated_by'] = Auth::user()->name;

        // Update Foto
        if ($request->hasFile('foto')) {
            if ($ncr->foto && file_exists(public_path('storage/' . $ncr->foto))) {
                unlink(public_path('storage/' . $ncr->foto));
            }

            $imageName = time() . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->move(public_path('storage/ncr'), $imageName);
            $data['foto'] = 'ncr/' . $imageName;
        }

        $ncr->update($data);

        return redirect()->route('adminsystem.ncr.index')->with('success', 'Data berhasil diperbarui!');
    }



    public function sent_update(Request $request, $id)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:255',
            'nama_hs_officer_1' => 'nullable|string|max:255',
            'nama_hs_officer_2' => 'nullable|string|max:255',
            'tanggal_audit' => 'required|date',
            'nama_auditee' => 'required|string|max:255',
            'perusahaan_id' => 'nullable|exists:perusahaan,id', // Ganti dari perusahaan_id
            'nama_bagian' => 'nullable|string|max:255',
            'element_referensi_ncr' => 'required|string|max:255',
            'kategori_ketidaksesuaian' => 'required|string|max:255',
            'estimasi' => 'required|date',
            'tindak_lanjut' => 'required|string',
            'foto' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:2048',
        ]);

        // Temukan record yang akan di-update
        $ncr_fixs = SentNcr::findOrFail($id);

        // Cari perusahaan berdasarkan kode

        // Ambil semua input kecuali foto & perusahaan_id
        $data = $request->except(['foto']);
        $data['status'] = 'Nothing';
        $data['updated_by'] = Auth::user()->name;

        // Update Foto jika ada
        if ($request->hasFile('foto')) {
            if ($ncr_fixs->foto && file_exists(public_path('storage/' . $ncr_fixs->foto))) {
                unlink(public_path('storage/' . $ncr_fixs->foto));
            }

            $imageName = time() . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->move(public_path('storage/ncr'), $imageName);
            $data['foto'] = 'ncr/' . $imageName;
        }

        $ncr_fixs->update($data);

        return redirect()->route('adminsystem.ncr.index')->with('success', 'Data berhasil diperbarui!');
    }


    // Menghapus data NCR berdasarkan ID
    public function destroy($id)
    {
        $ncr = Ncr::findOrFail($id);

        // Pindahkan data ke tabel ncr_fix
        SentNcr::create([
            'user_id' => $ncr->user_id,
            'tanggal_shift_kerja' => $ncr->tanggal_shift_kerja,
            'shift_kerja' => $ncr->shift_kerja,
            'nama_hs_officer_1' => $ncr->nama_hs_officer_1,
            'nama_hs_officer_2' => $ncr->nama_hs_officer_2,
            'tanggal_audit' => $ncr->tanggal_audit,
            'nama_auditee' => $ncr->nama_auditee,
            'perusahaan_id' => $ncr->perusahaan_id,
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

    public function draft_destroy(Request $request, $id)
    {
        // Ambil data PPE berdasarkan ID
        $ncr = Ncr::findOrFail($id);
        $ncr->delete();
        return redirect()->route('adminsystem.ncr.index')->with('success', 'NCR berhasil dikirim!');
    }
    public function sent_destroy(Request $request, $id)
    {
        // Ambil data PPE berdasarkan ID
        $ncr_fixs = SentNcr::findOrFail($id);
        $ncr_fixs->delete();
        // Redirect dengan notifikasi
        SentNcr::where('id', $request->sent_ncr_id)->update([
            'status' => 'Nothing',
        ]);
        return redirect()->route('adminsystem.ncr.index')->with('success', 'NCR berhasil dikirim!');
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
            'user_id' => Auth::user()->id,
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
    public function approve($sent_ncr_id)
    {
        $request = NcrRequest::findOrFail($sent_ncr_id);
        $request->status = 'Approved';
        $request->save();

        SentNcr::where('id', $request->sent_ncr_id)->update([
            'status' => 'Approved',
        ]);

        return redirect()->route('adminsystem.ncr.index')->with('success', 'Request berhasil disetujui.');
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
        return redirect()->route('adminsystem.ncr.index')->with('success', 'Request berhasil ditolak.');
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
            ->setPaper('a4', 'potrait');

        return $pdf->download('ncr.pdf');
    }

    public function exportSinglePdf($id)
    {
        $ncr_fixs = [SentNcr::findOrFail($id)];

        $pdf = Pdf::loadView('adminsystem.ncr.pdf', compact('ncr_fixs'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("ncr-{$id}.pdf");
    }

    public function close($id)
    {
        $ncr_fixs = SentNcr::findOrFail($id);

        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();

        return view('adminsystem.ncr.closed_ncr', compact('ncr_fixs', 'perusahaans', 'bagians'));
    }
    public function edit_close($id)
    {
        $ncr_fixs = SentNcr::findOrFail($id);

        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();

        return view('adminsystem.ncr.edit_closed', compact('ncr_fixs', 'perusahaans', 'bagians'));
    }
    public function updateClose(Request $request, $id)
    {
        $ncr = SentNcr::findOrFail($id);

        $rules = [
            'status_note' => 'required|string',
            'foto_closed' => $ncr->foto_closed ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $request->validate($rules);


        if ($request->hasFile('foto_closed')) {

            // Hapus file lama jika ada
            if ($ncr->foto_closed && file_exists(public_path('storage/' . $ncr->foto_closed))) {
                unlink(public_path('storage/' . $ncr->foto_closed));
            }

            // Simpan file baru ke folder public/storage/uploads/ncr
            $imageName = time() . '.' . $request->file('foto_closed')->extension();
            $request->file('foto_closed')->move(public_path('storage/uploads/ncr'), $imageName);

            // Simpan path relatif ke DB
            $ncr->foto_closed = 'uploads/ncr/' . $imageName;
        }

        $ncr->status_note = $request->status_note;
        $ncr->status_ncr = 'Closed';
        $ncr->status = 'Nothing';

        $ncr->save();

        return redirect()->route('adminsystem.ncr.index')->with('success', 'NCR berhasil ditutup.');
    }

    public function getBagian($code)
    {
        $bagians = Bagian::where('perusahaan_id', $code)->get();
        return response()->json($bagians);
    }





    public function close_ncr(Request $request, $id)
    {

        $ncr_fix = SentNcr::find($id);

        if (!$ncr_fix) {
            return response()->json(['message' => 'Data NCR tidak ditemukan'], 404);
        }

        // Validasi input
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:255',
            'nama_hs_officer_1' => 'nullable|string|max:255',
            'nama_hs_officer_2' => 'nullable|string|max:255',
            'tanggal_audit' => 'required|date',
            'nama_auditee' => 'required|string|max:255',










            'perusahaan_id' => 'required|max:255',
            'nama_bagian' => 'nullable|string|max:255',
            'element_referensi_ncr' => 'required|string|max:255',
            'kategori_ketidaksesuaian' => 'required|string|max:255',
            'estimasi' => 'required|string',
            'tindak_lanjut' => 'required|string',
            'foto' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:2048',
            'foto_closed' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:2048',
            'status_note' => 'required|string',
        ]);

        // Hitung durasi dari created_at
        $createdAt = \Carbon\Carbon::parse($ncr_fix->created_at);
        $now = now();
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

        // Upload foto baru (jika ada)
        if ($request->hasFile('foto_closed')) {
            // Hapus file lama
            if ($ncr_fix->foto_closed && file_exists(public_path('storage/' . $ncr_fix->foto_closed))) {
                unlink(public_path('storage/' . $ncr_fix->foto_closed));
            }

            // Simpan file baru
            $imageName = time() . '.' . $request->foto_closed->extension();
            $request->foto_closed->move(public_path('storage/closed'), $imageName);
            $ncr_fix->foto_closed = 'closed/' . $imageName;
        }

        // Update data NCR
        $ncr_fix->update([
            'tindak_lanjut' => $request->tindak_lanjut,
            'status_ncr' => 'Closed',
            'status_note' => $request->status_note,
            'durasi_ncr' => $durasi,
            'waktu_closed' => now(),
            'foto_closed' => $ncr_fix->foto_closed, // update jika ada
        ]);


        return redirect()->route('adminsystem.ncr.index')->with('success', 'NCR berhasil di-close!');
    }











    public function operator_index(Request $request)
    {
        $user = Auth::user();

        // Ambil data draft NCR oleh user login
        $ncrs = Ncr::where('user_id', $user->id)->get();

        // Ambil semua request (Edit/Delete)
        $allRequests = NcrRequest::all();
        $latestRequests = NcrRequest::orderByDesc('id')
            ->get()
            ->unique('sent_ncr_id');
        // Filter tanggal jika ada
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            $ncr_fixs = SentNcr::whereBetween('tanggal_shift_kerja', [$start, $end])->get();
        } else {
            $ncr_fixs = SentNcr::where('user_id', $user->id)->orderBy('tanggal_shift_kerja', 'desc')->get();
        }

        return view('operator.ncr.index', compact('ncrs', 'ncr_fixs', 'allRequests', 'latestRequests'));
    }


    // Menampilkan detail data NCR berdasarkan ID
    public function operator_show($id)
    {
        $ncr = Ncr::findOrFail($id);
        return view('operator.ncr.show', compact('ncr'));
    }
    public function operator_sent_show($id)
    {
        $ncr = SentNcr::findOrFail($id);
        return view('operator.ncr.show', compact('ncr'));
    }
    public function operator_exportSinglePdf($id)
    {
        $ncr_fixs = [SentNcr::findOrFail($id)];

        $pdf = Pdf::loadView('operator.ncr.pdf', compact('ncr_fixs'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("ncr-{$id}.pdf");
    }

    public function operator_closed_show($id)
    {
        $ncr = SentNcr::findOrFail($id);
        return view('operator.ncr.closed_show', compact('ncr'));
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

    public function operator_create()
    {
        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();

        // Mengirim data ke view
        return view('operator.ncr.report', compact('perusahaans', 'bagians'));
    }
    // Menambahkan data NCR baru
    public function operator_store(Request $request)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:255',
            'nama_hs_officer_1' => 'nullable|string|max:255',
            'nama_hs_officer_2' => 'nullable|string|max:255',
            'tanggal_audit' => 'required|date',
            'nama_auditee' => 'required|string|max:255',
            'perusahaan_id' => 'nullable|exists:perusahaan,id',
            'nama_bagian' => 'nullable|string|max:255',
            'element_referensi_ncr' => 'required|string|max:255',
            'kategori_ketidaksesuaian' => 'required|string|max:255',
            'estimasi' => 'required|date',
            'tindak_lanjut' => 'required|string',
            'foto' => 'required|file|mimes:pdf,jpeg,png,jpg,gif|max:2048',
        ]);

        // Ambil data perusahaan berdasarkan ID dari form

        // Ambil semua input dan tambahkan user info
        $data = $request->except(['foto']);
        $data['user_id'] = Auth::id();

        // Upload Foto
        if ($request->hasFile('foto')) {
            $imageName = time() . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->move(public_path('storage/ncr'), $imageName);
            $data['foto'] = 'ncr/' . $imageName;
        }

        // Simpan ke database
        Ncr::create($data);

        return redirect()->route('operator.ncr.index')->with('success', 'Data berhasil disimpan!');
    }

    // Memperbarui data NCR yang ada
    public function operator_update(Request $request, $id)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:255',
            'nama_hs_officer_1' => 'nullable|string|max:255',
            'nama_hs_officer_2' => 'nullable|string|max:255',
            'tanggal_audit' => 'required|date',
            'nama_auditee' => 'required|string|max:255',
            'perusahaan_id' => 'nullable|exists:perusahaan,id',
            'nama_bagian' => 'nullable|string|max:255',
            'element_referensi_ncr' => 'required|string|max:255',
            'kategori_ketidaksesuaian' => 'required|string|max:255',
            'estimasi' => 'required|date',
            'tindak_lanjut' => 'required|string',
            'foto' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:2048',
        ]);

        $ncr = Ncr::findOrFail($id);

        // Konversi perusahaan_id ke perusahaan

        $data = $request->except(['foto']);
        $data['updated_by'] = Auth::user()->name;

        // Update Foto
        if ($request->hasFile('foto')) {
            if ($ncr->foto && file_exists(public_path('storage/' . $ncr->foto))) {
                unlink(public_path('storage/' . $ncr->foto));
            }

            $imageName = time() . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->move(public_path('storage/ncr'), $imageName);
            $data['foto'] = 'ncr/' . $imageName;
        }

        $ncr->update($data);

        return redirect()->route('operator.ncr.index')->with('success', 'Data berhasil diperbarui!');
    }



    public function operator_sent_update(Request $request, $id)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:255',
            'nama_hs_officer_1' => 'nullable|string|max:255',
            'nama_hs_officer_2' => 'nullable|string|max:255',
            'tanggal_audit' => 'required|date',
            'nama_auditee' => 'required|string|max:255',
            'perusahaan_id' => 'nullable|exists:perusahaan,id', // Ganti dari perusahaan_id
            'nama_bagian' => 'nullable|string|max:255',
            'element_referensi_ncr' => 'required|string|max:255',
            'kategori_ketidaksesuaian' => 'required|string|max:255',
            'estimasi' => 'required|date',
            'tindak_lanjut' => 'required|string',
            'foto' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:2048',
        ]);

        // Temukan record yang akan di-update
        $ncr_fixs = SentNcr::findOrFail($id);

        // Cari perusahaan berdasarkan kode

        // Ambil semua input kecuali foto & perusahaan_id
        $data = $request->except(['foto']);
        $data['status'] = 'Nothing';
        $data['updated_by'] = Auth::user()->name;

        // Update Foto jika ada
        if ($request->hasFile('foto')) {
            if ($ncr_fixs->foto && file_exists(public_path('storage/' . $ncr_fixs->foto))) {
                unlink(public_path('storage/' . $ncr_fixs->foto));
            }

            $imageName = time() . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->move(public_path('storage/ncr'), $imageName);
            $data['foto'] = 'ncr/' . $imageName;
        }

        $ncr_fixs->update($data);

        return redirect()->route('operator.ncr.index')->with('success', 'Data berhasil diperbarui!');
    }
    // Menghapus data NCR berdasarkan ID
    public function operator_destroy($id)
    {
        $ncr = Ncr::findOrFail($id);

        // Pindahkan data ke tabel ncr_fix
        SentNcr::create([
            'user_id' => $ncr->user_id,
            'tanggal_shift_kerja' => $ncr->tanggal_shift_kerja,
            'shift_kerja' => $ncr->shift_kerja,
            'nama_hs_officer_1' => $ncr->nama_hs_officer_1,
            'nama_hs_officer_2' => $ncr->nama_hs_officer_2,
            'tanggal_audit' => $ncr->tanggal_audit,
            'nama_auditee' => $ncr->nama_auditee,
            'perusahaan_id' => $ncr->perusahaan_id,
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

        return redirect()->route('operator.ncr.index')->with('success', 'Data berhasil dikirim.');
    }

    public function operator_draft_destroy(Request $request, $id)
    {
        // Ambil data PPE berdasarkan ID
        $ncr = Ncr::findOrFail($id);
        $ncr->delete();
        return redirect()->route('operator.ncr.index')->with('success', 'NCR berhasil dikirim!');
    }
    public function operator_sent_destroy(Request $request, $id)
    {
        // Ambil data PPE berdasarkan ID
        $ncr_fixs = SentNcr::findOrFail($id);
        $ncr_fixs->delete();
        // Redirect dengan notifikasi
        SentNcr::where('id', $request->sent_ncr_id)->update([
            'status' => 'Nothing',
        ]);
        return redirect()->route('operator.ncr.index')->with('success', 'NCR berhasil dikirim!');
    }

    public function operator_storeRequest(Request $request)
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
            'user_id' => Auth::user()->id,
            'status' => 'Pending',
        ]);

        SentNcr::where('id', $request->sent_ncr_id)->update([
            'status' => 'Pending',
        ]);

        // Kirim email ke semua operator
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

        return redirect()->route('operator.ncr.index')->with('success', 'Request berhasil dikirim dan email telah dikirim ke admin.');
    }
    public function operator_approve($sent_ncr_id)
    {
        $request = NcrRequest::findOrFail($sent_ncr_id);
        $request->status = 'Approved';
        $request->save();

        SentNcr::where('id', $request->sent_ncr_id)->update([
            'status' => 'Approved',
        ]);

        return redirect()->route('operator.ncr.index');
    }


    public function operator_reject($id)
    {
        $request = NcrRequest::find($id);
        $request->status = 'Rejected';
        $request->save();
        // Update juga ncr_fixs jika perlu
        SentNcr::where('id', $request->sent_ncr_id)->update([
            'status' => 'Rejected',
        ]);
        return redirect()->route('operator.ncr.index');
    }
    public function operator_export(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            return Excel::download(new NcrExport($start, $end), 'ncr_filtered.xlsx');
        } else {
            return Excel::download(new NcrExport(null, null), 'ncr_all.xlsx');
        }
    }

    public function operator_exportPdf(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;
        $user = Auth::user();

        if ($start && $end) {
            $ncr_fixs = SentNcr::where('user_id', $user->id)
                ->whereBetween('tanggal_shift_kerja', [$start, $end])
                ->get();
        } else {
            $ncr_fixs = SentNcr::where('user_id', $user->id)->get();
        }
        $pdf = Pdf::loadView('operator.ncr.pdf', compact('ncr_fixs'))
            ->setPaper('a4', 'potrait');;

        return $pdf->download('ncr.pdf');
    }
    public function operator_close($id)
    {
        $ncr_fixs = SentNcr::findOrFail($id);

        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();

        return view('operator.ncr.closed_ncr', compact('ncr_fixs', 'perusahaans', 'bagians'));
    }
    public function operator_edit_close($id)
    {
        $ncr_fixs = SentNcr::findOrFail($id);

        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();

        return view('operator.ncr.edit_closed', compact('ncr_fixs', 'perusahaans', 'bagians'));
    }
    public function operator_updateClose(Request $request, $id)
    {
        $ncr = SentNcr::findOrFail($id);

        $rules = [
            'status_note' => 'required|string',
            'foto_closed' => $ncr->foto_closed ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $request->validate($rules);


        if ($request->hasFile('foto_closed')) {

            // Hapus file lama jika ada
            if ($ncr->foto_closed && file_exists(public_path('storage/' . $ncr->foto_closed))) {
                unlink(public_path('storage/' . $ncr->foto_closed));
            }

            // Simpan file baru ke folder public/storage/uploads/ncr
            $imageName = time() . '.' . $request->file('foto_closed')->extension();
            $request->file('foto_closed')->move(public_path('storage/uploads/ncr'), $imageName);

            // Simpan path relatif ke DB
            $ncr->foto_closed = 'uploads/ncr/' . $imageName;
        }

        $ncr->status_note = $request->status_note;
        $ncr->status_ncr = 'Closed';
        $ncr->status = 'Nothing';

        $ncr->save();

        return redirect()->route('operator.ncr.index')->with('success', 'NCR berhasil ditutup.');
    }


    public function operator_getBagian($perusahaan_name)
    {
        $bagians = Bagian::where('perusahaan_name', $perusahaan_name)->get();
        return response()->json($bagians);
    }
    public function operator_close_ncr(Request $request, $id)
    {
        $ncr_fix = SentNcr::find($id);

        if (!$ncr_fix) {
            return response()->json(['message' => 'Data NCR tidak ditemukan'], 404);
        }

        // Validasi input
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:255',
            'nama_hs_officer_1' => 'nullable|string|max:255',
            'nama_hs_officer_2' => 'nullable|string|max:255',
            'tanggal_audit' => 'required|date',
            'nama_auditee' => 'required|string|max:255',










            'nama_bagian' => 'nullable|string|max:255',
            'element_referensi_ncr' => 'required|string|max:255',
            'kategori_ketidaksesuaian' => 'required|string|max:255',
            'estimasi' => 'required|string',
            'tindak_lanjut' => 'required|string',
            'foto' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:2048',
            'foto_closed' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:2048',
            'status_note' => 'required|string',
        ]);

        // Hitung durasi dari created_at
        $createdAt = \Carbon\Carbon::parse($ncr_fix->created_at);
        $now = now();
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

        // Upload foto baru (jika ada)
        if ($request->hasFile('foto_closed')) {
            // Hapus file lama
            if ($ncr_fix->foto_closed && file_exists(public_path('storage/' . $ncr_fix->foto_closed))) {
                unlink(public_path('storage/' . $ncr_fix->foto_closed));
            }

            // Simpan file baru
            $imageName = time() . '.' . $request->foto_closed->extension();
            $request->foto_closed->move(public_path('storage/closed'), $imageName);
            $ncr_fix->foto_closed = 'closed/' . $imageName;
        }

        // Update data NCR
        $ncr_fix->update([
            'tanggal_shift_kerja' => $request->tanggal_shift_kerja,
            'shift_kerja' => $request->shift_kerja,
            'nama_hs_officer_1' => $request->nama_hs_officer_1,
            'nama_hs_officer_2' => $request->nama_hs_officer_2,
            'tanggal_audit' => $request->tanggal_audit,
            'nama_auditee' => $request->nama_auditee,
            'perusahaan' => $request->perusahaan,
            'nama_bagian' => $request->nama_bagian,
            'element_referensi_ncr' => $request->element_referensi_ncr,
            'kategori_ketidaksesuaian' => $request->kategori_ketidaksesuaian,
            'deskripsi_ketidaksesuaian' => $request->deskripsi_ketidaksesuaian,
            'estimasi' => $request->estimasi,
            'tindak_lanjut' => $request->tindak_lanjut,
            'status_ncr' => 'Closed',
            'status_note' => $request->status_note,
            'durasi_ncr' => $durasi,
            'waktu_closed' => now(),
            'foto_closed' => $ncr_fix->foto_closed, // update jika ada
        ]);

        return redirect()->route('operator.ncr.index')->with('success', 'NCR berhasil di-close!');
    }
}
