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
    public function getBagian($perusahaan_id)
    {
        $bagians = Bagian::where('perusahaan_id', $perusahaan_id)->get();
        return response()->json($bagians);
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_ppe'              => 'required|integer',
            'tipe_observasi'      => 'required|string',
            'nama_pelanggar'      => 'required|string',
            'perusahaan_id' => 'nullable|exists:perusahaan,id',
            'nama_bagian'         => 'required|string',
            'shift_kerja'         => 'required|string',
            'jam_mulai'           => 'required|string',
            'jam_selesai'         => 'required|string',
            'zona_pengawasan'     => 'required|string',
            'lokasi_observasi'    => 'required|string',
            'foto'                => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('foto'); // Ambil semua data kecuali file foto

        $data['user_id'] = Auth::id();

        if ($request->hasFile('foto')) {
            // Buat nama file unik dan simpan ke folder 'images' dalam storage/public
            $imageName = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('storage/pelanggar'), $imageName);
            $data['foto'] = 'pelanggar/' . $imageName;
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
            'perusahaan_id' => 'nullable|exists:perusahaan,id',
            'nama_bagian' => 'required|string',
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


        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($nonCompliant->foto && file_exists(public_path('storage/' . $nonCompliant->foto))) {
                unlink(public_path('storage/' . $nonCompliant->foto));
            }

            // Simpan foto baru
            $imageName = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('storage/pelanggar'), $imageName);
            $data['foto'] = 'pelanggar/' . $imageName;
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
            ->with('success', 'Data pelanggar berhasil dihapus!');
    }
    public function storeRequest(Request $request)
    {
        $request->validate([
            'sent_non_compliant_id' => 'required|exists:non_compliants,id',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        $non_compliantRequest = NonCompliantRequest::create([
            'sent_non_compliant_id' => $request->sent_non_compliant_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'user_id' => Auth::user()->id,
            'status' => 'Pending',
        ]);

        NonCompliant::where('id', $request->sent_non_compliant_id)->update([
            'status' => 'Pending',
        ]);

        // Kirim email ke semua adminsystem
        $admins = User::where('role', 'adminsystem')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NonCompliantRequestNotification($non_compliantRequest));
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
        // Ambil request, jika tidak ada akan lempar 404
        $request = NonCompliantRequest::findOrFail($id);

        // Set status Approved
        $request->status = 'Approved';
        $request->save();

        // Ambil data NonCompliant terkait
        $nonCompliant = NonCompliant::findOrFail($request->sent_non_compliant_id);
        $nonCompliant->status = 'Approved';
        $nonCompliant->save();

        // Redirect ke halaman PPE sesuai ID PPE dari NonCompliant
        return redirect()->route('adminsystem.ppe.show', $nonCompliant->id_ppe)
            ->with('success', 'Request berhasil disetujui.');
    }


    public function reject($id)
    {
        // Ambil request, jika tidak ada akan lempar 404
        $request = NonCompliantRequest::findOrFail($id);

        // Set status Approved
        $request->status = 'Rejected';
        $request->save();

        // Ambil data NonCompliant terkait
        $nonCompliant = NonCompliant::findOrFail($request->sent_non_compliant_id);
        $nonCompliant->status = 'Rejected';
        $nonCompliant->save();

        // Redirect ke halaman PPE sesuai ID PPE dari NonCompliant
        return redirect()->route('adminsystem.ppe.show', $nonCompliant->id_ppe)
            ->with('success', 'Request berhasil disetujui.');
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
    public function operator_getBagian($perusahaan_id)
    {
        $bagians = Bagian::where('perusahaan_id', $perusahaan_id)->get();
        return response()->json($bagians);
    }
    // Menyimpan data NonCompliant baru

    public function operator_store(Request $request)
    {
        $request->validate([
            'id_ppe'              => 'required|integer',
            'tipe_observasi'      => 'required|string',
            'nama_pelanggar'      => 'required|string',
            'perusahaan_id' => 'nullable|exists:perusahaan,id',
            'nama_bagian'         => 'required|string',
            'shift_kerja'         => 'required|string',
            'jam_mulai'           => 'required|string',
            'jam_selesai'         => 'required|string',
            'zona_pengawasan'     => 'required|string',
            'lokasi_observasi'    => 'required|string',
            'foto'                => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('foto'); // Ambil semua data kecuali file foto

        $data['user_id'] = Auth::id();

        if ($request->hasFile('foto')) {
            // Buat nama file unik dan simpan ke folder 'images' dalam storage/public
            $imageName = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('storage/pelanggar'), $imageName);
            $data['foto'] = 'pelanggar/' . $imageName;
        }

        NonCompliant::create($data);

        return redirect()->route('operator.ppe.show', $data['id_ppe'])
            ->with('success', 'Pelanggar berhasil ditambahkan!');
    }


    // Menampilkan form untuk mengedit NonCompliant
    public function operator_edit($id)
    {
        $nonCompliant = NonCompliant::findOrFail($id);
        $ppeFix = SentPpe::findOrFail($nonCompliant->id_ppe);
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
            'perusahaan_id' => 'nullable|exists:perusahaan,id',
            'nama_bagian' => 'required|string',
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


        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($nonCompliant->foto && file_exists(public_path('storage/' . $nonCompliant->foto))) {
                unlink(public_path('storage/' . $nonCompliant->foto));
            }

            // Simpan foto baru
            $imageName = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('storage/pelanggar'), $imageName);
            $data['foto'] = 'pelanggar/' . $imageName;
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
        $request->validate([
            'sent_non_compliant_id' => 'required|exists:non_compliants,id',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        $non_compliantRequest = NonCompliantRequest::create([
            'sent_non_compliant_id' => $request->sent_non_compliant_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'user_id' => Auth::user()->id,
            'status' => 'Pending',
        ]);

        NonCompliant::where('id', $request->sent_non_compliant_id)->update([
            'status' => 'Pending',
        ]);

        // Kirim email ke semua adminsystem
        $admins = User::where('role', 'adminsystem')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NonCompliantRequestNotification($non_compliantRequest));
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
