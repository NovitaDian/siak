<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Daily;
use App\Models\DailyRequest;
use App\Models\SentDaily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyController extends Controller
{

    // Menampilkan semua data observasi
    public function index()
    {
        $user = Auth::user(); // Get the currently authenticated user
        $dailys = Daily::where('writer', $user->name)->get();
        $daily_fixs = SentDaily::all();
        $requests = DailyRequest::all();
        return view('adminsystem.daily.index', compact('dailys', 'daily_fixs', 'requests'));
    }

    // Menampilkan form untuk membuat data baru
    public function create()
    {
        return view('adminsystem.daily.report');
    }

    // Menyimpan data baru ke database
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'nama_hs_officer' => 'required|string|max:100',
            'rincian_laporan' => 'nullable|string|max:255',
        ]);

        // Add the 'writer' field to the validated data
        $validatedData = $request->all();
        $validatedData['writer'] = Auth::user()->name;

        // Create a new record in the Daily model with the validated data
        Daily::create($validatedData);

        // Redirect with a success message
        return redirect()->route('adminsystem.daily.index')->with('success', 'Data berhasil disimpan!');
    }

    // Menampilkan detail data
    public function show($id)
    {
        $daily = Daily::findOrFail($id);
        return view('adminsystem.daily.show', compact('daily'));
    }

    // Menampilkan form edit data
    public function edit($id)
    {
        $daily = Daily::findOrFail($id);
        return view('adminsystem.daily.edit', compact('daily'));
    }

    // Mengupdate data ke database
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'nama_hs_officer' => 'required|string|max:100',
            'rincian_laporan' => 'nullable|string|max:255',
        ]);
    
        // Find the Daily record by ID or fail if not found
        $daily = Daily::findOrFail($id);
    
        // Add the 'writer' field to the validated data
        $validatedData = $request->all();
        $validatedData['writer'] = Auth::user()->name;
    
        // Update the existing record with the validated data
        $daily->update($validatedData);
    
        // Redirect with a success message
        return redirect()->route('adminsystem.daily.index')->with('success', 'Data berhasil diupdate!');
    }
    

    // Menghapus data dari database
    public function destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $daily = Daily::findOrFail($id);

        // Menyiapkan data untuk dimasukkan ke daily_fix, pastikan data diubah menjadi array
        $dataToInsert = $daily->toArray();

        // Memastikan created_at dan updated_at ditambahkan (jika tabel daily_fix memerlukannya)
        $dataToInsert['created_at'] = $daily->created_at;
        $dataToInsert['updated_at'] = $daily->updated_at;

        // Cek apakah data sudah ada di daily_fix berdasarkan ID atau kolom unik lainnya
        $exists = DB::table('daily_fix')->where('id', $daily->id)->exists();

        if (!$exists) {
            // Insert data hanya jika belum ada
            DB::table('daily_fix')->insert($dataToInsert);
        }

        // Hapus data daily asli
        $daily->delete();

        // Redirect dengan notifikasi
        return redirect()->route('adminsystem.daily.index')->with('notification', 'Laporan berhasil terkirim!');
    }
    public function storeRequest(Request $request)
    {
        // Validate input
        $request->validate([
            'sent_daily_id' => 'required|exists:daily_fix,id', 
            'type' => 'required|string',
            'reason' => 'required|string', 
        ]);
    
        // Save request to the daily_request table
        DailyRequest::create([
            'sent_daily_id' => $request->sent_daily_id, 
            'type' => $request->type, 
            'reason' => $request->reason, 
            'nama_pengirim' => Auth::user()->name,
        ]);
    
        // Return JSON response with a 201 status code (Created)
        return response()->json([
            'success' => true,
            'message' => 'Request submitted successfully.'
        ], 201);
    }
    
    public function sent_edit($id)
    {
        // Retrieve the NCR record by ID
        $daily_fixs = SentDaily::findOrFail($id);
        return view('adminsystem.daily.sent_edit', compact('daily_fixs'));
    }
    public function sent_update(Request $request, $id)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'nama_hs_officer' => 'required|string|max:100',
            'rincian_laporan' => 'nullable|string|max:255',
        ]);

        $daily = Daily::findOrFail($id);
        $daily->update($request->all());
        // Add the 'writer' field to the validated data
        $validatedData = $request->all();
        $validatedData['writer'] = Auth::user()->name;

        // Create a new record in the Daily model
        Daily::create($validatedData);

        return redirect()->route('adminsystem.daily.index')->with('success', 'Data berhasil diupdate!');
    }
    public function sent_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $daily_fixs = SentDaily::findOrFail($id);
        $daily_fixs->delete();
        // Redirect dengan notifikasi
        return redirect()->route('adminsystem.daily.index')->with('notification', 'NCR berhasil dikirim!');
    }
    public function approve($id)
    {
        $request = DailyRequest::find($id);
        $request->status = 'Approved';
        $request->save();

        return response()->json(['success' => true]);
    }

    public function reject($id)
    {
        $request = DailyRequest::find($id);
        $request->status = 'Rejected';
        $request->save();

        return response()->json(['success' => true]);
    }









    // OPERATOR
    // Menampilkan semua data observasi
    public function operator_index()
    {
        $user = Auth::user(); // Get the currently authenticated user
        $dailys = Daily::where('writer', $user->name)->get();
        $daily_fixs = SentDaily::where('writer', $user->name)->get();
        $requests = DailyRequest::all();
        return view('operator.daily.index', compact('dailys', 'daily_fixs', 'requests'));
    }

    // Menampilkan form untuk membuat data baru
    public function operator_create()
    {
        return view('operator.daily.report');
    }

    // Menyimpan data baru ke database
    public function operator_store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'nama_hs_officer' => 'required|string|max:100',
            'rincian_laporan' => 'nullable|string|max:255',
        ]);

        // Add the 'writer' field to the validated data
        $validatedData = $request->all();
        $validatedData['writer'] = Auth::user()->name;

        // Create a new record in the Daily model with the validated data
        Daily::create($validatedData);

        // Redirect with a success message
        return redirect()->route('operator.daily.index')->with('success', 'Data berhasil disimpan!');
    }

    // Menampilkan detail data
    public function operator_show($id)
    {
        $daily = Daily::findOrFail($id);
        return view('operator.daily.show', compact('daily'));
    }

    // Menampilkan form edit data
    public function operator_edit($id)
    {
        $daily = Daily::findOrFail($id);
        return view('operator.daily.edit', compact('daily'));
    }

    // Mengupdate data ke database
    public function operator_update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'nama_hs_officer' => 'required|string|max:100',
            'rincian_laporan' => 'nullable|string|max:255',
        ]);
    
        // Find the Daily record by ID or fail if not found
        $daily = Daily::findOrFail($id);
    
        // Add the 'writer' field to the validated data
        $validatedData = $request->all();
        $validatedData['writer'] = Auth::user()->name;
    
        // Update the existing record with the validated data
        $daily->update($validatedData);
    
        // Redirect with a success message
        return redirect()->route('operator.daily.index')->with('success', 'Data berhasil diupdate!');
    }
    

    // Menghapus data dari database
    public function operator_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $daily = Daily::findOrFail($id);

        // Menyiapkan data untuk dimasukkan ke daily_fix, pastikan data diubah menjadi array
        $dataToInsert = $daily->toArray();

        // Memastikan created_at dan updated_at ditambahkan (jika tabel daily_fix memerlukannya)
        $dataToInsert['created_at'] = $daily->created_at;
        $dataToInsert['updated_at'] = $daily->updated_at;

        // Cek apakah data sudah ada di daily_fix berdasarkan ID atau kolom unik lainnya
        $exists = DB::table('daily_fix')->where('id', $daily->id)->exists();

        if (!$exists) {
            // Insert data hanya jika belum ada
            DB::table('daily_fix')->insert($dataToInsert);
        }

        // Hapus data daily asli
        $daily->delete();

        // Redirect dengan notifikasi
        return redirect()->route('operator.daily.index')->with('notification', 'Laporan berhasil terkirim!');
    }
    public function operator_storeRequest(Request $request)
    {
        // Validate input
        $request->validate([
            'sent_daily_id' => 'required|exists:daily_fix,id', // Validate that the sent_daily_id exists in the daily_fix table
            'type' => 'required|string', // Ensure 'type' is required and is a string
            'reason' => 'required|string', // Ensure 'reason' is required and is a string
        ]);
    
        // Save request to the daily_request table
        DailyRequest::create([
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
    
    public function operator_sent_edit($id)
    {
        // Retrieve the NCR record by ID
        $daily_fixs = SentDaily::findOrFail($id);
        return view('operator.daily.sent_edit', compact('daily_fixs'));
    }
    public function operator_sent_update(Request $request, $id)
    {
        $request->validate([
            'tanggal_shift_kerja' => 'required|date',
            'shift_kerja' => 'required|string|max:50',
            'nama_hs_officer' => 'required|string|max:100',
            'rincian_laporan' => 'nullable|string|max:255',
        ]);

        $daily = Daily::findOrFail($id);
        $daily->update($request->all());
        // Add the 'writer' field to the validated data
        $validatedData = $request->all();
        $validatedData['writer'] = Auth::user()->name;

        // Create a new record in the Daily model
        Daily::create($validatedData);

        return redirect()->route('operator.daily.index')->with('success', 'Data berhasil diupdate!');
    }
    public function operator_sent_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $daily_fixs = SentDaily::findOrFail($id);
        $daily_fixs->delete();
        // Redirect dengan notifikasi
        return redirect()->route('operator.daily.index')->with('notification', 'NCR berhasil dikirim!');
    }
    public function operator_approve($id)
    {
        $request = DailyRequest::find($id);
        $request->status = 'Approved';
        $request->save();

        return response()->json(['success' => true]);
    }

    public function operator_reject($id)
    {
        $request = DailyRequest::find($id);
        $request->status = 'Rejected';
        $request->save();

        return response()->json(['success' => true]);
    }
}
