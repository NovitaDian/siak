<?php

namespace App\Http\Controllers;

use App\Exports\IncidentExport;
use App\Mail\IncidentRequestNotification;
use App\Mail\ToolRequestNotification;
use App\Models\Bagian;
use App\Models\HseInspector;
use App\Models\Incident;
use App\Models\IncidentRequest;
use App\Models\Perusahaan;
use App\Models\SentIncident;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;



class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $incidents = Incident::where('writer', $user->name)->get();
        $requests = IncidentRequest::all();
        $start = $request->start_date;
        $end = $request->end_date;

        // Jika filter tanggal diisi, gunakan whereBetween
        if ($start && $end) {
            $incident_fixs = SentIncident::whereBetween('shift_date', [$start, $end])->get();
        } else {
            $incident_fixs = SentIncident::all();
        }
        return view('adminsystem.incident.index', compact('incidents', 'incident_fixs', 'requests'));
    }

    // Form untuk membuat data baru (create)
    public function create()
    {
        $perusahaans = Perusahaan::all();
        $officers = HseInspector::all();
        $bagians = Bagian::all();
        return view('adminsystem.incident.report', compact('perusahaans', 'bagians', 'officers'));
    }

    // Menyimpan data baru (store)
    public function store(Request $request)
    {
        // Validasi data utama
        $validated = $request->validate([
            'stamp_date' => 'nullable|date_format:d/m/Y',
            'shift_date' => 'required|date',
            'shift' => 'required|string|max:255',
            'safety_officer_1' => 'required|string|max:255',
            'status_kejadian' => 'nullable|string|max:255',
            'tgl_kejadiannya' => 'nullable|date',
            'jam_kejadiannya' => 'nullable|date_format:H:i',
            'lokasi_kejadiannya' => 'nullable|string|max:255',
            'klasifikasi_kejadiannya' => 'nullable|string|max:255',
            'ada_korban' => 'nullable|string|max:255',
            'nama_korban' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:50',
            'perusahaan' => 'nullable|string|max:255',
            'bagian' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'masa_kerja' => 'nullable|integer',
            'tgl_lahir' => 'nullable|date',
            'jenis_luka_sakit' => 'nullable|string|max:255',
            'jenis_luka_sakit2' => 'nullable|string|max:255',
            'jenis_luka_sakit3' => 'nullable|string|max:255',
            'bagian_tubuh_luka' => 'nullable|string|max:255',
            'bagian_tubuh_luka2' => 'nullable|string|max:255',
            'bagian_tubuh_luka3' => 'nullable|string|max:255',
            'jenis_kejadiannya' => 'nullable|string|max:255',
            'penjelasan_kejadiannya' => 'nullable|string',
            'tindakan_pengobatan' => 'nullable|string',
            'tindakan_segera_yang_dilakukan' => 'nullable|string',
            'rincian_dari_pemeriksaan' => 'nullable|string',
            'penyebab_langsung_1_a' => 'nullable|string|max:255',
            'penyebab_langsung_1_b' => 'nullable|string|max:255',
            'penyebab_langsung_2_a' => 'nullable|string|max:255',
            'penyebab_langsung_2_b' => 'nullable|string|max:255',
            'penyebab_langsung_3_a' => 'nullable|string|max:255',
            'penyebab_langsung_3_b' => 'nullable|string|max:255',
            'penyebab_dasar_1_a' => 'nullable|string|max:255',
            'penyebab_dasar_1_b' => 'nullable|string|max:255',
            'penyebab_dasar_1_c' => 'nullable|string|max:255',
            'penyebab_dasar_2_a' => 'nullable|string|max:255',
            'penyebab_dasar_2_b' => 'nullable|string|max:255',
            'penyebab_dasar_2_c' => 'nullable|string|max:255',
            'penyebab_dasar_3_a' => 'nullable|string|max:255',
            'penyebab_dasar_3_b' => 'nullable|string|max:255',
            'penyebab_dasar_3_c' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_1_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_1_b' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_1' => 'nullable|string',
            'pic_1' => 'nullable|string|max:255',
            'timing_1' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_b' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_2' => 'nullable|string',
            'pic_2' => 'nullable|string|max:255',
            'timing_2' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_b' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_3' => 'nullable|string',
            'pic_3' => 'nullable|string|max:255',
            'timing_3' => 'nullable|string|max:255',
            'jml_employee' => 'nullable|integer',
            'jml_outsources' => 'nullable|integer',
            'jml_security' => 'nullable|integer',
            'jml_loading_stacking' => 'nullable|integer',
            'jml_contractor' => 'nullable|integer',
            'jml_hari_hilang' => 'nullable|integer',
            'bulan_tahun' => 'nullable|string|max:255',
            'ada' => 'nullable|boolean',
            'near_miss' => 'nullable|boolean',
            'illness_sick' => 'nullable|boolean',
            'first_aid_case' => 'nullable|boolean',
            'medical_treatment_case' => 'nullable|boolean',
            'restricted_work_case' => 'nullable|boolean',
            'lost_workdays_case' => 'nullable|boolean',
            'permanent_partial_dissability' => 'nullable|boolean',
            'permanent_total_dissability' => 'nullable|boolean',
            'fatality' => 'nullable|boolean',
            'fire_incident' => 'nullable|boolean',
            'road_incident' => 'nullable|boolean',
            'property_loss_damage' => 'nullable|boolean',
            'environmental_incident' => 'nullable|boolean',
            'total_lta_by_year' => 'nullable|integer',
            'total_wlta_by_year' => 'nullable|integer',
            'total_work_force' => 'nullable|integer',
            'man_hours_per_day' => 'nullable|integer',
            'total_man_hours' => 'nullable|integer',
            'safe_shift' => 'nullable|boolean',
            'safe_day' => 'nullable|boolean',
            'total_safe_day_by_year' => 'nullable|integer',
            'no_laporan' => 'nullable|string|max:255',
        ]);

        // Tanggal stamp & user
        $validated['stamp_date'] = Carbon::today()->toDateString();
        $validated['writer'] = auth()->user()->name;

        // Total tenaga kerja
        $totalWorkforce =
            ($request->input('jml_employee') ?? 0) +
            ($request->input('jml_outsources') ?? 0) +
            ($request->input('jml_security') ?? 0) +
            ($request->input('jml_loading_stacking') ?? 0) +
            ($request->input('jml_contractor') ?? 0);

        $validated['total_work_force'] = $totalWorkforce;

        // Total man hours per hari (diasumsikan 8 jam kerja)
        $validated['man_hours_per_day'] = $totalWorkforce * 8;

        // Hitung status korban
        $validated['ada'] = ($request->input('ada_korban') === 'Ada') ? 1 : 0;

        // Hitung bulan_tahun
        $validated['bulan_tahun'] = date('Y-m', strtotime($request->input('shift_date')));

        // Klasifikasi Kejadian
        $klasifikasi = $request->input('klasifikasi_kejadiannya');

        // Tentukan LTA & WLTA sesuai klasifikasi
        $validated['lta'] = in_array($klasifikasi, [
            'Lost Workdays Case (LWC)',
            'Permanent Partial Dissability (PPD)',
            'Permanent Total Dissability (PTD)'
        ]) ? 1 : 0;

        $validated['wlta'] = in_array($klasifikasi, [
            'First Aid Case (FAC)',
            'Medical Treatment Case (MTC)',
            'Restricted Work Case (RWC)'
        ]) ? 1 : 0;

        // TRC = LTA + WLTA
        $validated['trc'] = $validated['lta'] + $validated['wlta'];

        // Hitung total LTA tahun berjalan
        $validated['total_lta_by_year'] = Incident::whereIn('klasifikasi_kejadiannya', [
            'Lost Workdays Case (LWC)',
            'Permanent Partial Dissability (PPD)',
            'Permanent Total Dissability (PTD)'
        ])
            ->whereYear('shift_date', date('Y', strtotime($request->input('shift_date'))))
            ->count();

        // Hitung total WLTA tahun berjalan
        $validated['total_wlta_by_year'] = Incident::whereIn('klasifikasi_kejadiannya', [
            'First Aid Case (FAC)',
            'Medical Treatment Case (MTC)',
            'Restricted Work Case (RWC)'
        ])
            ->whereYear('shift_date', date('Y', strtotime($request->input('shift_date'))))
            ->count();

        // Hitung total man hours (contoh jika mau kalkulasi kumulatif per tahun)
        $validated['total_man_hours'] = Incident::whereYear('shift_date', date('Y', strtotime($request->input('shift_date'))))
            ->sum('man_hours_per_day') + $validated['man_hours_per_day'];

        // Simpan ke database
        $incident = Incident::create($validated);

        return redirect()->route('adminsystem.incident.index', $incident->id)
            ->with('success', 'Data berhasil ditambahkan.');
    }


    // Form untuk mengedit data (edit)
    public function edit($id)
    {
        $incidents = Incident::findOrFail($id);
        return view('adminsystem.incident.edit', compact('incidents'));
    }

    // Memperbarui data (update)
    public function update(Request $request, $id)
    {
        $request->validate([
            'stamp_date' => 'required|date',
            'shift_date' => 'required|date',
            'shift' => 'required|string|max:255',
            'safety_officer_1' => 'required|string|max:255',
            'status_kejadian' => 'required|string|max:255',
            'tgl_kejadiannya' => 'nullable|date',
            'jam_kejadiannya' => 'nullable|date_format:H:i:s',
            'lokasi_kejadiannya' => 'nullable|string|max:255',
            'klasifikasi_kejadiannya' => 'nullable|string|max:255',
            'ada_korban' => 'nullable|boolean',
            'nama_korban' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:50',
            'perusahaan' => 'nullable|string|max:255',
            'bagian' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'masa_kerja' => 'nullable|integer',
            'tgl_lahir' => 'nullable|date',
            'jenis_luka_sakit' => 'nullable|string|max:255',
            'jenis_luka_sakit2' => 'nullable|string|max:255',
            'jenis_luka_sakit3' => 'nullable|string|max:255',
            'bagian_tubuh_luka' => 'nullable|string|max:255',
            'bagian_tubuh_luka2' => 'nullable|string|max:255',
            'bagian_tubuh_luka3' => 'nullable|string|max:255',
            'jenis_kejadiannya' => 'nullable|string|max:255',
            'penjelasan_kejadiannya' => 'nullable|string',
            'tindakan_pengobatan' => 'nullable|string',
            'tindakan_segera_yang_dilakukan' => 'nullable|string',
            'rincian_dari_pemeriksaan' => 'nullable|string',
            'penyebab_langsung_1_a' => 'nullable|string|max:255',
            'penyebab_langsung_1_b' => 'nullable|string|max:255',
            'penyebab_langsung_2_a' => 'nullable|string|max:255',
            'penyebab_langsung_2_b' => 'nullable|string|max:255',
            'penyebab_langsung_3_a' => 'nullable|string|max:255',
            'penyebab_langsung_3_b' => 'nullable|string|max:255',
            'penyebab_dasar_1_a' => 'nullable|string|max:255',
            'penyebab_dasar_1_b' => 'nullable|string|max:255',
            'penyebab_dasar_1_c' => 'nullable|string|max:255',
            'penyebab_dasar_2_a' => 'nullable|string|max:255',
            'penyebab_dasar_2_b' => 'nullable|string|max:255',
            'penyebab_dasar_2_c' => 'nullable|string|max:255',
            'penyebab_dasar_3_a' => 'nullable|string|max:255',
            'penyebab_dasar_3_b' => 'nullable|string|max:255',
            'penyebab_dasar_3_c' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_1_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_1_b' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_1' => 'nullable|string',
            'pic_1' => 'nullable|string|max:255',
            'timing_1' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_b' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_2' => 'nullable|string',
            'pic_2' => 'nullable|string|max:255',
            'timing_2' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_b' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_3' => 'nullable|string',
            'pic_3' => 'nullable|string|max:255',
            'timing_3' => 'nullable|string|max:255',
            'jml_employee' => 'nullable|integer',
            'jml_outsources' => 'nullable|integer',
            'jml_security' => 'nullable|integer',
            'jml_loading_stacking' => 'nullable|integer',
            'jml_contractor' => 'nullable|integer',
            'jml_hari_hilang' => 'nullable|integer',
            'bulan_tahun' => 'nullable|string|max:255',
            'ada' => 'nullable|boolean',
            'near_miss' => 'nullable|boolean',
            'illness_sick' => 'nullable|boolean',
            'first_aid_case' => 'nullable|boolean',
            'medical_treatment_case' => 'nullable|boolean',
            'restricted_work_case' => 'nullable|boolean',
            'lost_workdays_case' => 'nullable|boolean',
            'permanent_partial_dissability' => 'nullable|boolean',
            'permanent_total_dissability' => 'nullable|boolean',
            'fatality' => 'nullable|boolean',
            'lta' => 'nullable|boolean',
            'wlta' => 'nullable|boolean',
            'trc' => 'nullable|boolean',
            'fire_incident' => 'nullable|boolean',
            'road_incident' => 'nullable|boolean',
            'property_loss_damage' => 'nullable|boolean',
            'environmental_incident' => 'nullable|boolean',
            'total_lta_by_year' => 'nullable|integer',
            'total_wlta_by_year' => 'nullable|integer',
            'total_work_force' => 'nullable|integer',
            'man_hours_per_day' => 'nullable|integer',
            'total_man_hours' => 'nullable|integer',
            'safe_shift' => 'nullable|boolean',
            'safe_day' => 'nullable|boolean',
            'total_safe_day_by_year' => 'nullable|integer',
            'no_laporan' => 'nullable|string|max:255',
        ]);

        $incidents = Incident::findOrFail($id);
        $incidents->update($request->all());

        return redirect()->route('adminsystem.incident.index')->with('success', 'Data berhasil diperbarui.');
    }

    // Melihat detail data (show)
    public function show($id)
    {
        $incident = Incident::find($id);
        if (!$incident) {
            abort(404, 'Data tidak ditemukan');
        }
        return view('adminsystem.incident.view ', compact('incident'));
    }

    // Menghapus data (destroy)
    public function destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $incidents = Incident::findOrFail($id);

        // Menyiapkan data untuk dimasukkan ke incidents_fix, pastikan data diubah menjadi array
        $dataToInsert = $incidents->toArray();

        // Memastikan created_at dan updated_at ditambahkan (jika tabel incidents_fix memerlukannya)
        $dataToInsert['created_at'] = $incidents->created_at;
        $dataToInsert['updated_at'] = $incidents->updated_at;

        // Cek apakah data sudah ada di incidents_fix berdasarkan ID atau kolom unik lainnya
        $exists = DB::table('incidents_fix')->where('id', $incidents->id)->exists();

        if (!$exists) {
            // Insert data hanya jika belum ada
            DB::table('incidents_fix')->insert($dataToInsert);
        }

        // Hapus data incidents asli
        $incidents->delete();

        // Redirect dengan notifikasi
        return redirect()->route('adminsystem.incident.index')->with('notification', 'Laporan berhasil dikirim!');
    }
    public function sent_destroy($id)
    {
        // Find the incident by ID or fail if not found
        $incident = Incident::findOrFail($id);

        // Delete the incident record
        $incident->delete();

        // Redirect to a relevant page, such as the incident index page with a success message
        return redirect()->route('adminsystem.incidents.index')->with('success', 'Incident deleted successfully.');
    }

    // Mencari data (search)
    public function search(Request $request)
    {
        $query = $request->input('query');
        $incidents = Incident::where('incident_type', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhere('status', 'LIKE', "%{$query}%")
            ->orWhere('no_laporan', 'LIKE', "%{$query}%")
            ->get();

        return view('adminsystem.incident.index', compact('incidents'))->with('success', 'Pencarian selesai.');
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'sent_incident_id' => 'required|exists:incidents_fix,id',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        $incidentRequest = IncidentRequest::create([
            'sent_incident_id' => $request->sent_incident_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => Auth::user()->name,
            'status' => 'Pending',
        ]);

        SentIncident::where('id', $request->sent_incident_id)->update([
            'status' => 'Pending',
        ]);

        // Kirim email ke semua adminsystem
        $admins = User::where('role', 'adminsystem')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new IncidentRequestNotification($incidentRequest));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Request berhasil dikirim dan email telah dikirim ke admin.',
            ]);
        }

        return redirect()->route('adminsystem.incident.index')->with('success', 'Request berhasil dikirim dan email telah dikirim ke admin.');
    }
    public function approve($id)
    {
        $request = IncidentRequest::find($id);
        $request->status = 'Approved';
        $request->save();

        return response()->json(['success' => true]);
    }

    public function reject($id)
    {
        $request = IncidentRequest::find($id);
        $request->status = 'Rejected';
        $request->save();

        return response()->json(['success' => true]);
    }
    public function getBagian($perusahaan_name)
    {
        $bagians = Bagian::where('perusahaan_name', $perusahaan_name)->get();
        return response()->json($bagians);
    }
    public function master()
    {
        $pers = Perusahaan::all();
        $bagians = Bagian::all();
        return view('adminsystem.incident.master', compact('pers', 'bagians'));
    }
    public function export(Request $request)
    {
        return Excel::download(new IncidentExport($request->start_date, $request->end_date), 'incident_report.xlsx');
    }
    public function exportPdf(Request $request)
    {
        $incidents = SentIncident::whereBetween('shift_date', [$request->start_date, $request->end_date])->get();

        $pdf = Pdf::loadView('adminsystem.incident.pdf', compact('incidents'));
        return $pdf->download('incident_report.pdf');
    }















    // OPERATOR

    public function operator_index()
    {
        $user = auth()->user();
        $incidents = Incident::where('writer', $user->name)->get();
        $requests = IncidentRequest::all();
        $incident_fixs = SentIncident::where('writer', $user->name)->get();
        return view('operator.incident.index', compact('incidents', 'incident_fixs', 'requests'));
    }

    // Form untuk membuat data baru (create)
    public function operator_create()
    {
        $perusahaans = Perusahaan::all();
        $bagians = Bagian::all();
        return view('operator.incident.report', compact('perusahaans', 'bagians'));
    }

    // Menyimpan data baru (store)
    public function operator_store(Request $request)
    {
        // Validasi data dari request
        $request->validate([
            'stamp_date' => 'nullable|date_format:d/m/Y',
            'shift_date' => 'required|date',
            'shift' => 'required|string|max:255',
            'safety_officer_1' => 'required|string|max:255',
            'status_kejadian' => 'nullable|string|max:255',
            'tgl_kejadiannya' => 'nullable|date',
            'jam_kejadiannya' => 'nullable|date_format:H:i',
            'lokasi_kejadiannya' => 'nullable|string|max:255',
            'klasifikasi_kejadiannya' => 'nullable|string|max:255',
            'ada_korban' => 'nullable|string|max:255',
            'nama_korban' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:50',
            'perusahaan' => 'nullable|string|max:255',
            'bagian' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'masa_kerja' => 'nullable|integer',
            'tgl_lahir' => 'nullable|date',
            'jenis_luka_sakit' => 'nullable|string|max:255',
            'jenis_luka_sakit2' => 'nullable|string|max:255',
            'jenis_luka_sakit3' => 'nullable|string|max:255',
            'bagian_tubuh_luka' => 'nullable|string|max:255',
            'bagian_tubuh_luka2' => 'nullable|string|max:255',
            'bagian_tubuh_luka3' => 'nullable|string|max:255',
            'jenis_kejadiannya' => 'nullable|string|max:255',
            'penjelasan_kejadiannya' => 'nullable|string',
            'tindakan_pengobatan' => 'nullable|string',
            'tindakan_segera_yang_dilakukan' => 'nullable|string',
            'rincian_dari_pemeriksaan' => 'nullable|string',
            'penyebab_langsung_1_a' => 'nullable|string|max:255',
            'penyebab_langsung_1_b' => 'nullable|string|max:255',
            'penyebab_langsung_2_a' => 'nullable|string|max:255',
            'penyebab_langsung_2_b' => 'nullable|string|max:255',
            'penyebab_langsung_3_a' => 'nullable|string|max:255',
            'penyebab_langsung_3_b' => 'nullable|string|max:255',
            'penyebab_dasar_1_a' => 'nullable|string|max:255',
            'penyebab_dasar_1_b' => 'nullable|string|max:255',
            'penyebab_dasar_1_c' => 'nullable|string|max:255',
            'penyebab_dasar_2_a' => 'nullable|string|max:255',
            'penyebab_dasar_2_b' => 'nullable|string|max:255',
            'penyebab_dasar_2_c' => 'nullable|string|max:255',
            'penyebab_dasar_3_a' => 'nullable|string|max:255',
            'penyebab_dasar_3_b' => 'nullable|string|max:255',
            'penyebab_dasar_3_c' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_1_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_1_b' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_1' => 'nullable|string',
            'pic_1' => 'nullable|string|max:255',
            'timing_1' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_b' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_2' => 'nullable|string',
            'pic_2' => 'nullable|string|max:255',
            'timing_2' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_b' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_3' => 'nullable|string',
            'pic_3' => 'nullable|string|max:255',
            'timing_3' => 'nullable|string|max:255',
            'jml_employee' => 'nullable|integer',
            'jml_outsources' => 'nullable|integer',
            'jml_security' => 'nullable|integer',
            'jml_loading_stacking' => 'nullable|integer',
            'jml_contractor' => 'nullable|integer',
            'jml_hari_hilang' => 'nullable|integer',
            'bulan_tahun' => 'nullable|string|max:255',
            'ada' => 'nullable|boolean',
            'near_miss' => 'nullable|boolean',
            'illness_sick' => 'nullable|boolean',
            'first_aid_case' => 'nullable|boolean',
            'medical_treatment_case' => 'nullable|boolean',
            'restricted_work_case' => 'nullable|boolean',
            'lost_workdays_case' => 'nullable|boolean',
            'permanent_partial_dissability' => 'nullable|boolean',
            'permanent_total_dissability' => 'nullable|boolean',
            'fatality' => 'nullable|boolean',
            'lta' => 'nullable|boolean',
            'wlta' => 'nullable|boolean',
            'trc' => 'nullable|boolean',
            'fire_incident' => 'nullable|boolean',
            'road_incident' => 'nullable|boolean',
            'property_loss_damage' => 'nullable|boolean',
            'environmental_incident' => 'nullable|boolean',
            'total_lta_by_year' => 'nullable|integer',
            'total_wlta_by_year' => 'nullable|integer',
            'total_work_force' => 'nullable|integer',
            'man_hours_per_day' => 'nullable|integer',
            'total_man_hours' => 'nullable|integer',
            'safe_shift' => 'nullable|boolean',
            'safe_day' => 'nullable|boolean',
            'total_safe_day_by_year' => 'nullable|integer',
            'no_laporan' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $data['stamp_date'] = Carbon::today()->toDateString();
        $data['writer'] = auth()->user()->name;
        $incident = Incident::create($data);
        return redirect()->route('adminsystem.operator.index', $incident->id)
            ->with('success', 'Data berhasil ditambahkan.');
    }

    // Form untuk mengedit data (edit)
    public function operator_edit($id)
    {
        $incidents = Incident::findOrFail($id);
        return view('operator.incident.edit', compact('incidents'));
    }

    // Memperbarui data (update)
    public function operator_update(Request $request, $id)
    {
        $request->validate([
            'stamp_date' => 'required|date',
            'shift_date' => 'required|date',
            'shift' => 'required|string|max:255',
            'safety_officer_1' => 'required|string|max:255',
            'status_kejadian' => 'required|string|max:255',
            'tgl_kejadiannya' => 'nullable|date',
            'jam_kejadiannya' => 'nullable|date_format:H:i:s',
            'lokasi_kejadiannya' => 'nullable|string|max:255',
            'klasifikasi_kejadiannya' => 'nullable|string|max:255',
            'ada_korban' => 'nullable|boolean',
            'nama_korban' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:50',
            'perusahaan' => 'nullable|string|max:255',
            'bagian' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'masa_kerja' => 'nullable|integer',
            'tgl_lahir' => 'nullable|date',
            'jenis_luka_sakit' => 'nullable|string|max:255',
            'jenis_luka_sakit2' => 'nullable|string|max:255',
            'jenis_luka_sakit3' => 'nullable|string|max:255',
            'bagian_tubuh_luka' => 'nullable|string|max:255',
            'bagian_tubuh_luka2' => 'nullable|string|max:255',
            'bagian_tubuh_luka3' => 'nullable|string|max:255',
            'jenis_kejadiannya' => 'nullable|string|max:255',
            'penjelasan_kejadiannya' => 'nullable|string',
            'tindakan_pengobatan' => 'nullable|string',
            'tindakan_segera_yang_dilakukan' => 'nullable|string',
            'rincian_dari_pemeriksaan' => 'nullable|string',
            'penyebab_langsung_1_a' => 'nullable|string|max:255',
            'penyebab_langsung_1_b' => 'nullable|string|max:255',
            'penyebab_langsung_2_a' => 'nullable|string|max:255',
            'penyebab_langsung_2_b' => 'nullable|string|max:255',
            'penyebab_langsung_3_a' => 'nullable|string|max:255',
            'penyebab_langsung_3_b' => 'nullable|string|max:255',
            'penyebab_dasar_1_a' => 'nullable|string|max:255',
            'penyebab_dasar_1_b' => 'nullable|string|max:255',
            'penyebab_dasar_1_c' => 'nullable|string|max:255',
            'penyebab_dasar_2_a' => 'nullable|string|max:255',
            'penyebab_dasar_2_b' => 'nullable|string|max:255',
            'penyebab_dasar_2_c' => 'nullable|string|max:255',
            'penyebab_dasar_3_a' => 'nullable|string|max:255',
            'penyebab_dasar_3_b' => 'nullable|string|max:255',
            'penyebab_dasar_3_c' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_1_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_1_b' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_1' => 'nullable|string',
            'pic_1' => 'nullable|string|max:255',
            'timing_1' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_b' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_2' => 'nullable|string',
            'pic_2' => 'nullable|string|max:255',
            'timing_2' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_b' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_3' => 'nullable|string',
            'pic_3' => 'nullable|string|max:255',
            'timing_3' => 'nullable|string|max:255',
            'jml_employee' => 'nullable|integer',
            'jml_outsources' => 'nullable|integer',
            'jml_security' => 'nullable|integer',
            'jml_loading_stacking' => 'nullable|integer',
            'jml_contractor' => 'nullable|integer',
            'jml_hari_hilang' => 'nullable|integer',
            'bulan_tahun' => 'nullable|string|max:255',
            'ada' => 'nullable|boolean',
            'near_miss' => 'nullable|boolean',
            'illness_sick' => 'nullable|boolean',
            'first_aid_case' => 'nullable|boolean',
            'medical_treatment_case' => 'nullable|boolean',
            'restricted_work_case' => 'nullable|boolean',
            'lost_workdays_case' => 'nullable|boolean',
            'permanent_partial_dissability' => 'nullable|boolean',
            'permanent_total_dissability' => 'nullable|boolean',
            'fatality' => 'nullable|boolean',
            'lta' => 'nullable|boolean',
            'wlta' => 'nullable|boolean',
            'trc' => 'nullable|boolean',
            'fire_incident' => 'nullable|boolean',
            'road_incident' => 'nullable|boolean',
            'property_loss_damage' => 'nullable|boolean',
            'environmental_incident' => 'nullable|boolean',
            'total_lta_by_year' => 'nullable|integer',
            'total_wlta_by_year' => 'nullable|integer',
            'total_work_force' => 'nullable|integer',
            'man_hours_per_day' => 'nullable|integer',
            'total_man_hours' => 'nullable|integer',
            'safe_shift' => 'nullable|boolean',
            'safe_day' => 'nullable|boolean',
            'total_safe_day_by_year' => 'nullable|integer',
            'no_laporan' => 'nullable|string|max:255',
        ]);

        $incidents = Incident::findOrFail($id);
        $incidents->update($request->all());

        return redirect()->route('operator.incident.index')->with('success', 'Data berhasil diperbarui.');
    }

    // Melihat detail data (show)
    public function operator_show($id)
    {
        $incidents = Incident::findOrFail($id);
        return view('operator.incident.view', compact('incidents'));
    }

    // Menghapus data (destroy)
    public function operator_destroy($id)
    {
        // Ambil data PPE berdasarkan ID
        $incidents = Incident::findOrFail($id);

        // Menyiapkan data untuk dimasukkan ke incidents_fix, pastikan data diubah menjadi array
        $dataToInsert = $incidents->toArray();

        // Memastikan created_at dan updated_at ditambahkan (jika tabel incidents_fix memerlukannya)
        $dataToInsert['created_at'] = $incidents->created_at;
        $dataToInsert['updated_at'] = $incidents->updated_at;

        // Cek apakah data sudah ada di incidents_fix berdasarkan ID atau kolom unik lainnya
        $exists = DB::table('incidents_fix')->where('id', $incidents->id)->exists();

        if (!$exists) {
            // Insert data hanya jika belum ada
            DB::table('incidents_fix')->insert($dataToInsert);
        }

        // Hapus data incidents asli
        $incidents->delete();

        // Redirect dengan notifikasi
        return redirect()->route('operator.incident.index')->with('notification', 'Laporan berhasil dikirim!');
    }
    public function operator_sent_destroy($id)
    {
        // Find the incident by ID or fail if not found
        $incident = Incident::findOrFail($id);

        // Delete the incident record
        $incident->delete();

        // Redirect to a relevant page, such as the incident index page with a success message
        return redirect()->route('operator.incidents.index')->with('success', 'Incident deleted successfully.');
    }

    // Mencari data (search)
    public function operator_search(Request $request)
    {
        $query = $request->input('query');
        $incidents = Incident::where('incident_type', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhere('status', 'LIKE', "%{$query}%")
            ->orWhere('no_laporan', 'LIKE', "%{$query}%")
            ->get();

        return view('operator.incident.index', compact('incidents'))->with('success', 'Pencarian selesai.');
    }

    public function operator_storeRequest(Request $request)
    {
        // Validate input
        $request->validate([
            'sent_incident_id' => 'required|exists:incidents_fix,id',
            'type' => 'required|string',
            'reason' => 'required|string',
        ]);

        // Save request to the incident_request table
        IncidentRequest::create([
            'sent_incident_id' => $request->sent_incident_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'nama_pengirim' => auth()->user()->name,
        ]);

        // Return JSON response
        return response()->json(['success' => true, 'message' => 'Request submitted successfully.']);
    }
    public function operator_approve($id)
    {
        $request = IncidentRequest::find($id);
        $request->status = 'Approved';
        $request->save();

        return response()->json(['success' => true]);
    }

    public function operator_reject($id)
    {
        $request = IncidentRequest::find($id);
        $request->status = 'Rejected';
        $request->save();

        return response()->json(['success' => true]);
    }
}
