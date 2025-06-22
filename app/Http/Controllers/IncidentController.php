<?php

namespace App\Http\Controllers;

use App\Exports\IncidentExport;
use App\Mail\IncidentRequestNotification;
use App\Mail\ToolRequestNotification;
use App\Models\Bagian;
use App\Models\HariHilang;
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
        $incidents = Incident::where('writer', $user->name)->latest()
            ->get();
        $requests = IncidentRequest::latest()
            ->get();

        // Ambil semua request (Edit/Delete)
        $latestRequests = IncidentRequest::orderByDesc('id')
            ->get()
            ->unique('sent_incident_id');
        $start = $request->start_date;
        $end = $request->end_date;

        // Jika filter tanggal diisi, gunakan whereBetween
        if ($start && $end) {
            $incident_fixs = SentIncident::whereBetween('shift_date', [$start, $end])->orderBy('shift_date', 'desc')
                ->get();
        } else {
            $incident_fixs = SentIncident::orderByDesc('shift_date')->orderBy('shift_date', 'desc')->get();
        }

        return view('adminsystem.incident.index', compact('incidents', 'incident_fixs', 'requests','latestRequests'));
    }

    // Form untuk membuat data baru (create)
    public function create()
    {
        $perusahaans = Perusahaan::all();
        $officers = HseInspector::all();
        $bagians = Bagian::all();
        $hilangs = HariHilang::all();
        return view('adminsystem.incident.report', compact('perusahaans', 'bagians', 'officers', 'hilangs'));
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
            'tindakan_kendali_untuk_peningkatan_1_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_1' => 'nullable|string',
            'pic_1' => 'nullable|string|max:255',
            'timing_1' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_b' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_2' => 'nullable|string',
            'pic_2' => 'nullable|string|max:255',
            'timing_2' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_b' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_3' => 'nullable|string',
            'pic_3' => 'nullable|string|max:255',
            'timing_3' => 'nullable|string|max:255',
            'jml_employee' => 'nullable|integer',
            'jml_outsources' => 'nullable|integer',
            'jml_security' => 'nullable|integer',
            'jml_loading_stacking' => 'nullable|integer',
            'jml_contractor' => 'nullable|integer',
            'jml_hari_hilang' => 'nullable|integer',
            'ada' => 'nullable|string',
            'no_laporan' => 'nullable|string|max:255',
        ]);

        // Tanggal stamp & user
        $validated['stamp_date'] = Carbon::today()->toDateString();
        $validated['user_id'] = auth()->user()->id;
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
        // Hitung bulan_tahun
        $validated['shift_date'] = date('Y-m-d', strtotime($request->input('shift_date')));

        // Klasifikasi Kejadian
        $klasifikasi = $request->input('klasifikasi_kejadiannya');
        // Tentukan LTA & WLTA sesuai klasifikasi
        $validated['lta'] = in_array($klasifikasi, [
            'Lost Workdays Case (LWC)',
            'Permanent Partial Disability (PPD)',
            'Permanent Total Disability (PTD)'
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
            'Permanent Partial Disability (PPD)',
            'Permanent Total Disability (PTD)'
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
        // Hitung safe_shift
        if (
            ($validated['lta'] ?? 0) === 0 &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Fire Incident' &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Road Incident'
        ) {
            $validated['safe_shift'] = 1;
        } else {
            $validated['safe_shift'] = 0;
        }

        // Hitung safe_day
        if (
            ($validated['lta'] ?? 0) === 0 &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Fire Incident'
        ) {
            $validated['safe_day'] = 1;
        } else {
            $validated['safe_day'] = 0;
        }


        // PENGHITUNGAN TOTAL SAFE DAY BY YEAR
        try {
            $shiftDate = $request->input('shift_date'); // contoh: 2025-01-01
            $shift = $request->input('shift');
            $safeDay = $validated['safe_day']; // input hari ini
            $safeShift = $validated['safe_shift']; // input hari ini, 1 atau 0

            // Jika shift_date adalah "01 JAN" dan shift adalah "SHIFT I"
            $isFirstDay = (date('d M', strtotime($shiftDate)) === '01 Jan') && ($shift === 'SHIFT I');

            if ($isFirstDay) {
                $validated['total_safe_day_by_year'] = $safeDay;
            } elseif ($safeShift == 1) {
                // Hitung jumlah total safe_day sebelumnya (di tahun yang sama)
                $totalSafeDaySebelumnya = Incident::whereYear('shift_date', date('Y', strtotime($shiftDate)))
                    ->where('shift_date', '<', $shiftDate)
                    ->sum('safe_day');

                $validated['total_safe_day_by_year'] = $safeDay + $totalSafeDaySebelumnya;
            } else {
                $validated['total_safe_day_by_year'] = 0;
            }
        } catch (\Exception $e) {
            // Jika ada error apapun, fallback ke 0
            $validated['total_safe_day_by_year'] = 0;
        }
        // PERHITUNGAN TOTAL SAFE DAY LTA 2
        try {
            $safeShift = $validated['safe_shift']; // 1 atau 0
            $safeDay = $validated['safe_day'];
            $shiftDate = $request->input('shift_date');

            if (!$safeShift) {
                $validated['total_safe_day_lta2'] = 0;
            } else {
                $totalSafeDayLTA2Sebelumnya = Incident::where('shift_date', '<', $shiftDate)
                    ->whereYear('shift_date', date('Y', strtotime($shiftDate)))
                    ->sum('safe_day');

                $validated['total_safe_day_lta2'] = $safeDay + $totalSafeDayLTA2Sebelumnya;
            }
        } catch (\Exception $e) {
            $validated['total_safe_day_lta2'] = 0;
        }
        // === SAFE SHIFT WLTA ===
        // Jika WLTA = 0, maka aman
        $validated['safe_shift_wlta'] = ($validated['wlta'] == 0) ? 1 : 0;

        // === SAFE DAY WLTA ===
        // Bandingkan dengan tanggal sebelumnya (asumsi berdasarkan shift_date dan shift)
        $prevIncident = Incident::where('shift_date', '<', $validated['shift_date'])
            ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
            ->orderByDesc('shift_date')
            ->first();

        if ($prevIncident && $prevIncident->shift_date == $validated['shift_date']) {
            $validated['safe_day_wlta'] = 0;
        } else {
            $validated['safe_day_wlta'] = ($validated['safe_shift_wlta'] === 1) ? 1 : 0;
        }

        // === TOTAL SAFE DAY WLTA ===
        if ($validated['safe_shift_wlta'] === 0) {
            $validated['total_safe_day_wlta'] = 0;
        } else {
            $totalSafeDayWltaSebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('safe_day_wlta');

            $validated['total_safe_day_wlta'] = $validated['safe_day_wlta'] + $totalSafeDayWltaSebelumnya;
        }

        // === SAFE SHIFT WLTA ===
        $validated['safe_shift_wlta'] = $validated['wlta'] === 0 ? 1 : 0;


        $shiftOrder = [
            'Shift 1' => 1,
            'Shift 2' => 2,
            'Shift 3' => 3,
            'Nonshift' => 4,
        ];

        $currentDate = Carbon::parse($validated['shift_date']);
        $currentShift = $validated['shift'] ?? 'Shift 1';
        $currentShiftOrder = $shiftOrder[$currentShift] ?? 1;

        if ($validated['safe_shift'] == 1) {
            // Hitung total man hours sebelum laporan saat ini (berdasarkan tanggal dan urutan shift)
            $totalManHoursLtaSebelumnya = Incident::where(function ($query) use ($currentDate, $currentShiftOrder) {
                $query->where('shift_date', '<', $currentDate)
                    ->orWhere(function ($q) use ($currentDate, $currentShiftOrder) {
                        $q->where('shift_date', $currentDate)
                            ->whereRaw("CASE shift 
                WHEN 'Shift 1' THEN 1 
                WHEN 'Shift 2' THEN 2 
                WHEN 'Shift 3' THEN 3 
                WHEN 'Nonshift' THEN 4 
                ELSE 5 END < ?", [$currentShiftOrder]);
                    });
            })
                ->whereYear('shift_date', $currentDate->year)
                ->sum('man_hours_per_day');


            $validated['total_man_hours_lta'] = (int) ($validated['man_hours_per_day'] ?? 0) + $totalManHoursLtaSebelumnya;
        } else {
            $validated['total_man_hours_lta'] = 0;
        }


        // === TOTAL MAN HOURS WLTA ===
        if ($validated['safe_shift_wlta'] === 1) {
            $totalManHoursWltaSebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('man_hours_per_day');

            $validated['total_man_hours_wlta'] = $validated['man_hours_per_day'] + $totalManHoursWltaSebelumnya;
        } else {
            $validated['total_man_hours_wlta'] = 0;
        }

        if ($validated['safe_shift_wlta'] === 1) {
            // Ambil total_man_hours_wlta2 sebelumnya (kumulatif)
            $totalManHoursWlta2Sebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('total_man_hours_wlta2');

            // Tambah man_hours_per_day hari ini
            $validated['total_man_hours_wlta2'] = ($validated['man_hours_per_day'] ?? 0) + $totalManHoursWlta2Sebelumnya;
        } else {
            $validated['total_man_hours_wlta2'] = 0;
        }
        // Hitung no_laporan otomatis

        // Ambil shift_date dari request
        $shiftDate = Carbon::parse($request->shift_date);

        // Simpan ke database
        $incident = Incident::create($validated);

        return redirect()->route('adminsystem.incident.index', $incident->id)
            ->with('success', 'Data berhasil ditambahkan.');
    }


    // Form untuk mengedit data (edit)
    public function edit($id)
    {
        $incidents = Incident::findOrFail($id);
        $perusahaans = Perusahaan::all();
        $officers = HseInspector::all();
        $bagians = Bagian::all();
        $hilangs = HariHilang::all();
        return view('adminsystem.incident.edit', compact('incidents', 'perusahaans', 'bagians', 'officers', 'hilangs'));
    }
    public function sent_edit($id)
    {
        $incident_fix = SentIncident::findOrFail($id);
        $perusahaans = Perusahaan::all();
        $officers = HseInspector::all();
        $bagians = Bagian::all();
        $hilangs = HariHilang::all();
        return view('adminsystem.incident.sent_edit', compact('incident_fix', 'perusahaans', 'bagians', 'officers', 'hilangs'));
    }

    // Memperbarui data (update)
    public function update(Request $request, $id)
    {
        // Ambil data incident yang akan diupdate
        $incident = Incident::findOrFail($id);

        // Validasi awal (sesuaikan dengan kebutuhan)
        $validated = $request->validate([
            'stamp_date' => 'nullable|date_format:d/m/Y',
            'shift_date' => 'required|date',
            'shift' => 'required|string|max:255',
            'safety_officer_1' => 'required|string|max:255',
            'status_kejadian' => 'nullable|string|max:255',
            'tgl_kejadiannya' => 'nullable|date',
            'jam_kejadiannya' => 'nullable',
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
            'tindakan_kendali_untuk_peningkatan_1_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_1' => 'nullable|string',
            'pic_1' => 'nullable|string|max:255',
            'timing_1' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_b' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_2' => 'nullable|string',
            'pic_2' => 'nullable|string|max:255',
            'timing_2' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_b' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_3' => 'nullable|string',
            'pic_3' => 'nullable|string|max:255',
            'timing_3' => 'nullable|string|max:255',
            'jml_employee' => 'nullable|integer',
            'jml_outsources' => 'nullable|integer',
            'jml_security' => 'nullable|integer',
            'jml_loading_stacking' => 'nullable|integer',
            'jml_contractor' => 'nullable|integer',
            'jml_hari_hilang' => 'nullable|integer',
            'ada' => 'nullable|string',
            'no_laporan' => 'nullable|string|max:255',
        ]);

        // Tanggal stamp & user
        $validated['stamp_date'] = Carbon::today()->toDateString();
        $validated['user_id'] = auth()->user()->id;
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
        // Hitung bulan_tahun
        $validated['shift_date'] = date('Y-m-d', strtotime($request->input('shift_date')));

        // Klasifikasi Kejadian
        $klasifikasi = $request->input('klasifikasi_kejadiannya');
        // Tentukan LTA & WLTA sesuai klasifikasi
        $validated['lta'] = in_array($klasifikasi, [
            'Lost Workdays Case (LWC)',
            'Permanent Partial Disability (PPD)',
            'Permanent Total Disability (PTD)'
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
            'Permanent Partial Disability (PPD)',
            'Permanent Total Disability (PTD)'
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
        // Hitung safe_shift
        if (
            ($validated['lta'] ?? 0) === 0 &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Fire Incident' &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Road Incident'
        ) {
            $validated['safe_shift'] = 1;
        } else {
            $validated['safe_shift'] = 0;
        }

        // Hitung safe_day
        if (
            ($validated['lta'] ?? 0) === 0 &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Fire Incident'
        ) {
            $validated['safe_day'] = 1;
        } else {
            $validated['safe_day'] = 0;
        }


        // PENGHITUNGAN TOTAL SAFE DAY BY YEAR
        try {
            $shiftDate = $request->input('shift_date'); // contoh: 2025-01-01
            $shift = $request->input('shift');
            $safeDay = $validated['safe_day']; // input hari ini
            $safeShift = $validated['safe_shift']; // input hari ini, 1 atau 0

            // Jika shift_date adalah "01 JAN" dan shift adalah "SHIFT I"
            $isFirstDay = (date('d M', strtotime($shiftDate)) === '01 Jan') && ($shift === 'SHIFT I');

            if ($isFirstDay) {
                $validated['total_safe_day_by_year'] = $safeDay;
            } elseif ($safeShift == 1) {
                // Hitung jumlah total safe_day sebelumnya (di tahun yang sama)
                $totalSafeDaySebelumnya = Incident::whereYear('shift_date', date('Y', strtotime($shiftDate)))
                    ->where('shift_date', '<', $shiftDate)
                    ->sum('safe_day');

                $validated['total_safe_day_by_year'] = $safeDay + $totalSafeDaySebelumnya;
            } else {
                $validated['total_safe_day_by_year'] = 0;
            }
        } catch (\Exception $e) {
            // Jika ada error apapun, fallback ke 0
            $validated['total_safe_day_by_year'] = 0;
        }
        // PERHITUNGAN TOTAL SAFE DAY LTA 2
        try {
            $safeShift = $validated['safe_shift']; // 1 atau 0
            $safeDay = $validated['safe_day'];
            $shiftDate = $request->input('shift_date');

            if (!$safeShift) {
                $validated['total_safe_day_lta2'] = 0;
            } else {
                $totalSafeDayLTA2Sebelumnya = Incident::where('shift_date', '<', $shiftDate)
                    ->whereYear('shift_date', date('Y', strtotime($shiftDate)))
                    ->sum('safe_day');

                $validated['total_safe_day_lta2'] = $safeDay + $totalSafeDayLTA2Sebelumnya;
            }
        } catch (\Exception $e) {
            $validated['total_safe_day_lta2'] = 0;
        }
        // === SAFE SHIFT WLTA ===
        // Jika WLTA = 0, maka aman
        $validated['safe_shift_wlta'] = ($validated['wlta'] == 0) ? 1 : 0;

        // === SAFE DAY WLTA ===
        // Bandingkan dengan tanggal sebelumnya (asumsi berdasarkan shift_date dan shift)
        $prevIncident = Incident::where('shift_date', '<', $validated['shift_date'])
            ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
            ->orderByDesc('shift_date')
            ->first();

        if ($prevIncident && $prevIncident->shift_date == $validated['shift_date']) {
            $validated['safe_day_wlta'] = 0;
        } else {
            $validated['safe_day_wlta'] = ($validated['safe_shift_wlta'] === 1) ? 1 : 0;
        }

        // === TOTAL SAFE DAY WLTA ===
        if ($validated['safe_shift_wlta'] === 0) {
            $validated['total_safe_day_wlta'] = 0;
        } else {
            $totalSafeDayWltaSebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('safe_day_wlta');

            $validated['total_safe_day_wlta'] = $validated['safe_day_wlta'] + $totalSafeDayWltaSebelumnya;
        }

        // === SAFE SHIFT WLTA ===
        $validated['safe_shift_wlta'] = $validated['wlta'] === 0 ? 1 : 0;


        $shiftOrder = [
            'Shift 1' => 1,
            'Shift 2' => 2,
            'Shift 3' => 3,
            'Nonshift' => 4,
        ];

        $currentDate = Carbon::parse($validated['shift_date']);
        $currentShift = $validated['shift'] ?? 'Shift 1';
        $currentShiftOrder = $shiftOrder[$currentShift] ?? 1;

        if ($validated['safe_shift'] == 1) {
            // Hitung total man hours sebelum laporan saat ini (berdasarkan tanggal dan urutan shift)
            $totalManHoursLtaSebelumnya = Incident::where(function ($query) use ($currentDate, $currentShiftOrder) {
                $query->where('shift_date', '<', $currentDate)
                    ->orWhere(function ($q) use ($currentDate, $currentShiftOrder) {
                        $q->where('shift_date', $currentDate)
                            ->whereRaw("CASE shift 
                WHEN 'Shift 1' THEN 1 
                WHEN 'Shift 2' THEN 2 
                WHEN 'Shift 3' THEN 3 
                WHEN 'Nonshift' THEN 4 
                ELSE 5 END < ?", [$currentShiftOrder]);
                    });
            })
                ->whereYear('shift_date', $currentDate->year)
                ->sum('man_hours_per_day');


            $validated['total_man_hours_lta'] = (int) ($validated['man_hours_per_day'] ?? 0) + $totalManHoursLtaSebelumnya;
        } else {
            $validated['total_man_hours_lta'] = 0;
        }


        // === TOTAL MAN HOURS WLTA ===
        if ($validated['safe_shift_wlta'] === 1) {
            $totalManHoursWltaSebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('man_hours_per_day');

            $validated['total_man_hours_wlta'] = $validated['man_hours_per_day'] + $totalManHoursWltaSebelumnya;
        } else {
            $validated['total_man_hours_wlta'] = 0;
        }

        if ($validated['safe_shift_wlta'] === 1) {
            // Ambil total_man_hours_wlta2 sebelumnya (kumulatif)
            $totalManHoursWlta2Sebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('total_man_hours_wlta2');

            // Tambah man_hours_per_day hari ini
            $validated['total_man_hours_wlta2'] = ($validated['man_hours_per_day'] ?? 0) + $totalManHoursWlta2Sebelumnya;
        } else {
            $validated['total_man_hours_wlta2'] = 0;
        }
        // Hitung no_laporan otomatis

        // Ambil shift_date dari request
        $shiftDate = Carbon::parse($request->shift_date);

        // Hitung jumlah kejadian pada bulan & tahun yang sama
        $incidentCount = SentIncident::whereMonth('shift_date', $shiftDate->month)
            ->whereYear('shift_date', $shiftDate->year)
            ->count() + 1;

        // Mapping bulan ke angka Romawi
        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        // Ambil angka romawi berdasarkan bulan dari shiftDate
        $bulan = $bulanRomawi[(int)$shiftDate->format('n')];

        // Tahun format 2 digit
        $tahun2Digit = $shiftDate->format('y');

        // Susun no_laporan akhir
        $no_laporan = "{$incidentCount}/{$bulan}/HSE/{$tahun2Digit}";
        unset($validated['no_laporan']);

        // Update semua nilai ke dalam model

        $incident->update($validated);

        return redirect()->route('adminsystem.incident.index')
            ->with('success', 'Data berhasil diperbarui.');
    }
    public function sent_update(Request $request, $id)
    {
        // Ambil data incident yang akan diupdate
        $incident = SentIncident::findOrFail($id);

        // Validasi awal (sesuaikan dengan kebutuhan)
        $validated = $request->validate([
            'stamp_date' => 'nullable|date_format:d/m/Y',
            'shift_date' => 'required|date',
            'shift' => 'required|string|max:255',
            'safety_officer_1' => 'required|string|max:255',
            'status_kejadian' => 'nullable|string|max:255',
            'tgl_kejadiannya' => 'nullable|date',
            'jam_kejadiannya' => 'nullable',
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
            'tindakan_kendali_untuk_peningkatan_1_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_1' => 'nullable|string',
            'pic_1' => 'nullable|string|max:255',
            'timing_1' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_b' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_2' => 'nullable|string',
            'pic_2' => 'nullable|string|max:255',
            'timing_2' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_b' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_3' => 'nullable|string',
            'pic_3' => 'nullable|string|max:255',
            'timing_3' => 'nullable|string|max:255',
            'jml_employee' => 'nullable|integer',
            'jml_outsources' => 'nullable|integer',
            'jml_security' => 'nullable|integer',
            'jml_loading_stacking' => 'nullable|integer',
            'jml_contractor' => 'nullable|integer',
            'jml_hari_hilang' => 'nullable|integer',
            'ada' => 'nullable|string',
            'no_laporan' => 'nullable|string|max:255',
        ]);

        // Tanggal stamp & user
        $validated['stamp_date'] = Carbon::today()->toDateString();
        $validated['user_id'] = auth()->user()->id;
        $validated['writer'] = auth()->user()->name;
        $validated['status_request'] = "Nothing";

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
        // Hitung bulan_tahun
        $validated['shift_date'] = date('Y-m-d', strtotime($request->input('shift_date')));

        // Klasifikasi Kejadian
        $klasifikasi = $request->input('klasifikasi_kejadiannya');
        // Tentukan LTA & WLTA sesuai klasifikasi
        $validated['lta'] = in_array($klasifikasi, [
            'Lost Workdays Case (LWC)',
            'Permanent Partial Disability (PPD)',
            'Permanent Total Disability (PTD)'
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
            'Permanent Partial Disability (PPD)',
            'Permanent Total Disability (PTD)'
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
        // Hitung safe_shift
        if (
            ($validated['lta'] ?? 0) === 0 &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Fire Incident' &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Road Incident'
        ) {
            $validated['safe_shift'] = 1;
        } else {
            $validated['safe_shift'] = 0;
        }

        // Hitung safe_day
        if (
            ($validated['lta'] ?? 0) === 0 &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Fire Incident'
        ) {
            $validated['safe_day'] = 1;
        } else {
            $validated['safe_day'] = 0;
        }


        // PENGHITUNGAN TOTAL SAFE DAY BY YEAR
        try {
            $shiftDate = $request->input('shift_date'); // contoh: 2025-01-01
            $shift = $request->input('shift');
            $safeDay = $validated['safe_day']; // input hari ini
            $safeShift = $validated['safe_shift']; // input hari ini, 1 atau 0

            // Jika shift_date adalah "01 JAN" dan shift adalah "SHIFT I"
            $isFirstDay = (date('d M', strtotime($shiftDate)) === '01 Jan') && ($shift === 'SHIFT I');

            if ($isFirstDay) {
                $validated['total_safe_day_by_year'] = $safeDay;
            } elseif ($safeShift == 1) {
                // Hitung jumlah total safe_day sebelumnya (di tahun yang sama)
                $totalSafeDaySebelumnya = Incident::whereYear('shift_date', date('Y', strtotime($shiftDate)))
                    ->where('shift_date', '<', $shiftDate)
                    ->sum('safe_day');

                $validated['total_safe_day_by_year'] = $safeDay + $totalSafeDaySebelumnya;
            } else {
                $validated['total_safe_day_by_year'] = 0;
            }
        } catch (\Exception $e) {
            // Jika ada error apapun, fallback ke 0
            $validated['total_safe_day_by_year'] = 0;
        }
        // PERHITUNGAN TOTAL SAFE DAY LTA 2
        try {
            $safeShift = $validated['safe_shift']; // 1 atau 0
            $safeDay = $validated['safe_day'];
            $shiftDate = $request->input('shift_date');

            if (!$safeShift) {
                $validated['total_safe_day_lta2'] = 0;
            } else {
                $totalSafeDayLTA2Sebelumnya = Incident::where('shift_date', '<', $shiftDate)
                    ->whereYear('shift_date', date('Y', strtotime($shiftDate)))
                    ->sum('safe_day');

                $validated['total_safe_day_lta2'] = $safeDay + $totalSafeDayLTA2Sebelumnya;
            }
        } catch (\Exception $e) {
            $validated['total_safe_day_lta2'] = 0;
        }
        // === SAFE SHIFT WLTA ===
        // Jika WLTA = 0, maka aman
        $validated['safe_shift_wlta'] = ($validated['wlta'] == 0) ? 1 : 0;

        // === SAFE DAY WLTA ===
        // Bandingkan dengan tanggal sebelumnya (asumsi berdasarkan shift_date dan shift)
        $prevIncident = Incident::where('shift_date', '<', $validated['shift_date'])
            ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
            ->orderByDesc('shift_date')
            ->first();

        if ($prevIncident && $prevIncident->shift_date == $validated['shift_date']) {
            $validated['safe_day_wlta'] = 0;
        } else {
            $validated['safe_day_wlta'] = ($validated['safe_shift_wlta'] === 1) ? 1 : 0;
        }

        // === TOTAL SAFE DAY WLTA ===
        if ($validated['safe_shift_wlta'] === 0) {
            $validated['total_safe_day_wlta'] = 0;
        } else {
            $totalSafeDayWltaSebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('safe_day_wlta');

            $validated['total_safe_day_wlta'] = $validated['safe_day_wlta'] + $totalSafeDayWltaSebelumnya;
        }

        // === SAFE SHIFT WLTA ===
        $validated['safe_shift_wlta'] = $validated['wlta'] === 0 ? 1 : 0;


        $shiftOrder = [
            'Shift 1' => 1,
            'Shift 2' => 2,
            'Shift 3' => 3,
            'Nonshift' => 4,
        ];

        $currentDate = Carbon::parse($validated['shift_date']);
        $currentShift = $validated['shift'] ?? 'Shift 1';
        $currentShiftOrder = $shiftOrder[$currentShift] ?? 1;

        if ($validated['safe_shift'] == 1) {
            // Hitung total man hours sebelum laporan saat ini (berdasarkan tanggal dan urutan shift)
            $totalManHoursLtaSebelumnya = Incident::where(function ($query) use ($currentDate, $currentShiftOrder) {
                $query->where('shift_date', '<', $currentDate)
                    ->orWhere(function ($q) use ($currentDate, $currentShiftOrder) {
                        $q->where('shift_date', $currentDate)
                            ->whereRaw("CASE shift 
                WHEN 'Shift 1' THEN 1 
                WHEN 'Shift 2' THEN 2 
                WHEN 'Shift 3' THEN 3 
                WHEN 'Nonshift' THEN 4 
                ELSE 5 END < ?", [$currentShiftOrder]);
                    });
            })
                ->whereYear('shift_date', $currentDate->year)
                ->sum('man_hours_per_day');


            $validated['total_man_hours_lta'] = (int) ($validated['man_hours_per_day'] ?? 0) + $totalManHoursLtaSebelumnya;
        } else {
            $validated['total_man_hours_lta'] = 0;
        }


        // === TOTAL MAN HOURS WLTA ===
        if ($validated['safe_shift_wlta'] === 1) {
            $totalManHoursWltaSebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('man_hours_per_day');

            $validated['total_man_hours_wlta'] = $validated['man_hours_per_day'] + $totalManHoursWltaSebelumnya;
        } else {
            $validated['total_man_hours_wlta'] = 0;
        }

        if ($validated['safe_shift_wlta'] === 1) {
            // Ambil total_man_hours_wlta2 sebelumnya (kumulatif)
            $totalManHoursWlta2Sebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('total_man_hours_wlta2');

            // Tambah man_hours_per_day hari ini
            $validated['total_man_hours_wlta2'] = ($validated['man_hours_per_day'] ?? 0) + $totalManHoursWlta2Sebelumnya;
        } else {
            $validated['total_man_hours_wlta2'] = 0;
        }
        // Hitung no_laporan otomatis

        // Ambil shift_date dari request
        $shiftDate = Carbon::parse($request->shift_date);

        // Hitung jumlah kejadian pada bulan & tahun yang sama
        $incidentCount = SentIncident::whereMonth('shift_date', $shiftDate->month)
            ->whereYear('shift_date', $shiftDate->year)
            ->count() + 1;

        unset($validated['no_laporan']);

        // Update semua nilai ke dalam model

        $incident->update($validated);

        return redirect()->route('adminsystem.incident.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    // Melihat detail data (show)
    public function show($id)
    {
        $incident = Incident::find($id);

        if (!$incident) {
            abort(404, 'Data tidak ditemukan');
        }
        return view('adminsystem.incident.show ', compact('incident'));
    }
    public function sent_show($id)
    {
        $incident = SentIncident::find($id);

        if (!$incident) {
            abort(404, 'Data tidak ditemukan');
        }
        return view('adminsystem.incident.view ', compact('incident'));
    }

    // Menghapus data (destroy)
    public function destroy($id)
    {
        $incident = Incident::findOrFail($id);
        $shiftDate = $incident->shift_date;
        // Hitung jumlah kejadian pada bulan & tahun yang sama
        $incidentCount = SentIncident::whereMonth('shift_date', $shiftDate->month)
            ->whereYear('shift_date', $shiftDate->year)
            ->count() + 1;

        // Mapping bulan ke angka Romawi
        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        // Ambil angka romawi berdasarkan bulan dari shiftDate
        $bulan = $bulanRomawi[(int)$shiftDate->format('n')];

        // Tahun format 2 digit
        $tahun2Digit = $shiftDate->format('y');

        // Susun no_laporan akhir
        $no_laporan = "{$incidentCount}/{$bulan}/HSE/{$tahun2Digit}";
        // Pindahkan data ke tabel incident_fix
        SentIncident::create([
            'writer' => $incident->writer,
            'user_id' => $incident->user_id,
            'stamp_date' => $incident->stamp_date,
            'shift_date' => $incident->shift_date,
            'shift' => $incident->shift,
            'safety_officer_1' => $incident->safety_officer_1,
            'status_kejadian' => $incident->status_kejadian,
            'tgl_kejadiannya' => $incident->tgl_kejadiannya,
            'jam_kejadiannya' => $incident->jam_kejadiannya,
            'lokasi_kejadiannya' => $incident->lokasi_kejadiannya,
            'klasifikasi_kejadiannya' => $incident->klasifikasi_kejadiannya,
            'ada_korban' => $incident->ada_korban,
            'nama_korban' => $incident->nama_korban,
            'status' => $incident->status,
            'jenis_kelamin' => $incident->jenis_kelamin,
            'perusahaan' => $incident->perusahaan,
            'bagian' => $incident->bagian,
            'jabatan' => $incident->jabatan,
            'masa_kerja' => $incident->masa_kerja,
            'tgl_lahir' => $incident->tgl_lahir,
            'jenis_luka_sakit' => $incident->jenis_luka_sakit,
            'jenis_luka_sakit2' => $incident->jenis_luka_sakit2,
            'jenis_luka_sakit3' => $incident->jenis_luka_sakit3,
            'bagian_tubuh_luka' => $incident->bagian_tubuh_luka,
            'bagian_tubuh_luka2' => $incident->bagian_tubuh_luka2,
            'bagian_tubuh_luka3' => $incident->bagian_tubuh_luka3,
            'jenis_kejadiannya' => $incident->jenis_kejadiannya,
            'penjelasan_kejadiannya' => $incident->penjelasan_kejadiannya,
            'tindakan_pengobatan' => $incident->tindakan_pengobatan,
            'tindakan_segera_yang_dilakukan' => $incident->tindakan_segera_yang_dilakukan,
            'rincian_dari_pemeriksaan' => $incident->rincian_dari_pemeriksaan,
            'penyebab_langsung_1_a' => $incident->penyebab_langsung_1_a,
            'penyebab_langsung_1_b' => $incident->penyebab_langsung_1_b,
            'penyebab_langsung_2_a' => $incident->penyebab_langsung_2_a,
            'penyebab_langsung_2_b' => $incident->penyebab_langsung_2_b,
            'penyebab_langsung_3_a' => $incident->penyebab_langsung_3_a,
            'penyebab_langsung_3_b' => $incident->penyebab_langsung_3_b,
            'penyebab_dasar_1_a' => $incident->penyebab_dasar_1_a,
            'penyebab_dasar_1_b' => $incident->penyebab_dasar_1_b,
            'penyebab_dasar_1_c' => $incident->penyebab_dasar_1_c,
            'penyebab_dasar_2_a' => $incident->penyebab_dasar_2_a,
            'penyebab_dasar_2_b' => $incident->penyebab_dasar_2_b,
            'penyebab_dasar_2_c' => $incident->penyebab_dasar_2_c,
            'penyebab_dasar_3_a' => $incident->penyebab_dasar_3_a,
            'penyebab_dasar_3_b' => $incident->penyebab_dasar_3_b,
            'penyebab_dasar_3_c' => $incident->penyebab_dasar_3_c,
            'tindakan_kendali_untuk_peningkatan_1_a' => $incident->tindakan_kendali_untuk_peningkatan_1_a,
            'tindakan_kendali_untuk_peningkatan_1_b' => $incident->tindakan_kendali_untuk_peningkatan_1_b,
            'tindakan_kendali_untuk_peningkatan_1_c' => $incident->tindakan_kendali_untuk_peningkatan_1_c,
            'deskripsi_tindakan_pencegahan_1' => $incident->deskripsi_tindakan_pencegahan_1,
            'pic_1' => $incident->pic_1,
            'timing_1' => $incident->timing_1,
            'tindakan_kendali_untuk_peningkatan_2_a' => $incident->tindakan_kendali_untuk_peningkatan_2_a,
            'tindakan_kendali_untuk_peningkatan_2_b' => $incident->tindakan_kendali_untuk_peningkatan_2_b,
            'tindakan_kendali_untuk_peningkatan_2_c' => $incident->tindakan_kendali_untuk_peningkatan_2_c,
            'deskripsi_tindakan_pencegahan_2' => $incident->deskripsi_tindakan_pencegahan_2,
            'pic_2' => $incident->pic_2,
            'timing_2' => $incident->timing_2,
            'tindakan_kendali_untuk_peningkatan_3_a' => $incident->tindakan_kendali_untuk_peningkatan_3_a,
            'tindakan_kendali_untuk_peningkatan_3_b' => $incident->tindakan_kendali_untuk_peningkatan_3_b,
            'tindakan_kendali_untuk_peningkatan_3_c' => $incident->tindakan_kendali_untuk_peningkatan_3_c,
            'deskripsi_tindakan_pencegahan_3' => $incident->deskripsi_tindakan_pencegahan_3,
            'pic_3' => $incident->pic_3,
            'timing_3' => $incident->timing_3,
            'jml_employee' => $incident->jml_employee,
            'jml_outsources' => $incident->jml_outsources,
            'jml_security' => $incident->jml_security,
            'jml_loading_stacking' => $incident->jml_loading_stacking,
            'jml_contractor' => $incident->jml_contractor,
            'jml_hari_hilang' => $incident->jml_hari_hilang,
            'ada' => $incident->ada,
            'lta' => $incident->lta,
            'wlta' => $incident->wlta,
            'trc' => $incident->trc,
            'fire_incident' => $incident->fire_incident,
            'road_incident' => $incident->road_incident,
            'property_loss_damage' => $incident->property_loss_damage,
            'environmental_incident' => $incident->environmental_incident,
            'total_lta_by_year' => $incident->total_lta_by_year,
            'total_wlta_by_year' => $incident->total_wlta_by_year,
            'total_work_force' => $incident->total_work_force,
            'man_hours_per_day' => $incident->man_hours_per_day,
            'total_man_hours' => $incident->total_man_hours,
            'safe_shift' => $incident->safe_shift,
            'safe_day' => $incident->safe_day,
            'total_safe_day_by_year' => $incident->total_safe_day_by_year,
            'total_safe_day_lta2' => $incident->total_safe_day_lta2,
            'total_man_hours_lta' => $incident->total_man_hours_lta,
            'total_man_hours_wlta2' => $incident->total_man_hours_wlta2,
            'safe_shift_wlta' => $incident->safe_shift_wlta,
            'safe_day_wlta' => $incident->safe_day_wlta,
            'total_safe_day_wlta' => $incident->total_safe_day_wlta,
            'foto' => $incident->foto,
            'status_request' => 'Nothing',
            'draft_id' => $incident->id,
            'no_laporan' => $no_laporan,
        ]);



        // Hapus data dari incident
        $incident->delete();
        // Redirect dengan notifikasi
        return redirect()->route('adminsystem.incident.index')->with('notification', 'Laporan berhasil dikirim!');
    }
    public function draft_destroy($id)
    {
        $incident = Incident::findOrFail($id);

        // Delete the incident record
        $incident->delete();

        // Redirect to a relevant page, such as the incident index page with a success message
        return redirect()->route('adminsystem.incident.index')->with('success', 'Incident deleted successfully.');
    }
    public function sent_destroy($id)
    {
        $incident = SentIncident::findOrFail($id);

        // Delete the incident record
        $incident->delete();

        // Redirect to a relevant page, such as the incident index page with a success message
        return redirect()->route('adminsystem.incident.index')->with('success', 'Incident deleted successfully.');
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
            'user_id' => Auth::user()->id,
            'status' => 'Pending',
        ]);

        SentIncident::where('id', $request->sent_incident_id)->update([
            'status_request' => 'Pending',
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
        SentIncident::where('id', $request->sent_incident_id)->update(['status_request' => 'Approved']);

        return redirect()->route('adminsystem.incident.index')->with('success', 'Request berhasil disetujui.');
    }
    public function reject($id)
    {
        $request = IncidentRequest::find($id);
        $request->status = 'Rejected';
        $request->save();
        SentIncident::where('id', $request->sent_incident_id)->update(['status_request' => 'Rejected']);

        return redirect()->route('adminsystem.incident.index')->with('success', 'Request berhasil ditolak.');
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
    public function getJumlahHariHilang(Request $request)
    {
        $total = 0;

        foreach (['jenis1', 'jenis2', 'jenis3'] as $field) {
            $jenis = $request->input($field);
            if ($jenis) {
                $nilai = DB::table('jumlah_hari_hilang')
                    ->where('jenis_luka', $jenis)
                    ->value('jml_hari_hilang');
                $total += (int) ($nilai ?? 0);
            }
        }

        return response()->json(['total' => $total]);
    }
    public function downloadSatuan($id)
    {
        $incident = SentIncident::findOrFail($id);
        $pdf = Pdf::loadView('adminsystem.incident.view', compact('incident'));
        return $pdf->download('Incident_Report_' . $incident->id . '.pdf');
    }














   public function operator_index(Request $request)
    {
        $user = auth()->user();
        $incidents = Incident::where('writer', $user->name)->latest()
            ->get();
        $requests = IncidentRequest::latest()
            ->get();

        // Ambil semua request (Edit/Delete)
        $latestRequests = IncidentRequest::orderByDesc('id')
            ->get()
            ->unique('sent_incident_id');
        $start = $request->start_date;
        $end = $request->end_date;

       // Jika filter tanggal diisi, gunakan whereBetween
        if ($start && $end) {
            $incident_fixs = SentIncident::whereBetween('shift_date', [$start, $end])->orderBy('shift_date', 'desc')->get();
        } else {
            $incident_fixs = SentIncident::where('writer', $user->name)->orderBy('shift_date', 'desc')->get();
        }

        return view('operator.incident.index', compact('incidents', 'incident_fixs', 'requests','latestRequests'));
    }

    // Form untuk membuat data baru (create)
    public function operator_create()
    {
        $perusahaans = Perusahaan::all();
        $officers = HseInspector::all();
        $bagians = Bagian::all();
        $hilangs = HariHilang::all();
        return view('operator.incident.report', compact('perusahaans', 'bagians', 'officers', 'hilangs'));
    }

    // Menyimpan data baru (store)
    public function operator_store(Request $request)
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
            'tindakan_kendali_untuk_peningkatan_1_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_1' => 'nullable|string',
            'pic_1' => 'nullable|string|max:255',
            'timing_1' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_b' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_2' => 'nullable|string',
            'pic_2' => 'nullable|string|max:255',
            'timing_2' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_b' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_3' => 'nullable|string',
            'pic_3' => 'nullable|string|max:255',
            'timing_3' => 'nullable|string|max:255',
            'jml_employee' => 'nullable|integer',
            'jml_outsources' => 'nullable|integer',
            'jml_security' => 'nullable|integer',
            'jml_loading_stacking' => 'nullable|integer',
            'jml_contractor' => 'nullable|integer',
            'jml_hari_hilang' => 'nullable|integer',
            'ada' => 'nullable|string',
            'no_laporan' => 'nullable|string|max:255',
        ]);

        // Tanggal stamp & user
        $validated['stamp_date'] = Carbon::today()->toDateString();
        $validated['user_id'] = auth()->user()->id;
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
        // Hitung bulan_tahun
        $validated['shift_date'] = date('Y-m-d', strtotime($request->input('shift_date')));

        // Klasifikasi Kejadian
        $klasifikasi = $request->input('klasifikasi_kejadiannya');
        // Tentukan LTA & WLTA sesuai klasifikasi
        $validated['lta'] = in_array($klasifikasi, [
            'Lost Workdays Case (LWC)',
            'Permanent Partial Disability (PPD)',
            'Permanent Total Disability (PTD)'
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
            'Permanent Partial Disability (PPD)',
            'Permanent Total Disability (PTD)'
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
        // Hitung safe_shift
        if (
            ($validated['lta'] ?? 0) === 0 &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Fire Incident' &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Road Incident'
        ) {
            $validated['safe_shift'] = 1;
        } else {
            $validated['safe_shift'] = 0;
        }

        // Hitung safe_day
        if (
            ($validated['lta'] ?? 0) === 0 &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Fire Incident'
        ) {
            $validated['safe_day'] = 1;
        } else {
            $validated['safe_day'] = 0;
        }


        // PENGHITUNGAN TOTAL SAFE DAY BY YEAR
        try {
            $shiftDate = $request->input('shift_date'); // contoh: 2025-01-01
            $shift = $request->input('shift');
            $safeDay = $validated['safe_day']; // input hari ini
            $safeShift = $validated['safe_shift']; // input hari ini, 1 atau 0

            // Jika shift_date adalah "01 JAN" dan shift adalah "SHIFT I"
            $isFirstDay = (date('d M', strtotime($shiftDate)) === '01 Jan') && ($shift === 'SHIFT I');

            if ($isFirstDay) {
                $validated['total_safe_day_by_year'] = $safeDay;
            } elseif ($safeShift == 1) {
                // Hitung jumlah total safe_day sebelumnya (di tahun yang sama)
                $totalSafeDaySebelumnya = Incident::whereYear('shift_date', date('Y', strtotime($shiftDate)))
                    ->where('shift_date', '<', $shiftDate)
                    ->sum('safe_day');

                $validated['total_safe_day_by_year'] = $safeDay + $totalSafeDaySebelumnya;
            } else {
                $validated['total_safe_day_by_year'] = 0;
            }
        } catch (\Exception $e) {
            // Jika ada error apapun, fallback ke 0
            $validated['total_safe_day_by_year'] = 0;
        }
        // PERHITUNGAN TOTAL SAFE DAY LTA 2
        try {
            $safeShift = $validated['safe_shift']; // 1 atau 0
            $safeDay = $validated['safe_day'];
            $shiftDate = $request->input('shift_date');

            if (!$safeShift) {
                $validated['total_safe_day_lta2'] = 0;
            } else {
                $totalSafeDayLTA2Sebelumnya = Incident::where('shift_date', '<', $shiftDate)
                    ->whereYear('shift_date', date('Y', strtotime($shiftDate)))
                    ->sum('safe_day');

                $validated['total_safe_day_lta2'] = $safeDay + $totalSafeDayLTA2Sebelumnya;
            }
        } catch (\Exception $e) {
            $validated['total_safe_day_lta2'] = 0;
        }
        // === SAFE SHIFT WLTA ===
        // Jika WLTA = 0, maka aman
        $validated['safe_shift_wlta'] = ($validated['wlta'] == 0) ? 1 : 0;

        // === SAFE DAY WLTA ===
        // Bandingkan dengan tanggal sebelumnya (asumsi berdasarkan shift_date dan shift)
        $prevIncident = Incident::where('shift_date', '<', $validated['shift_date'])
            ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
            ->orderByDesc('shift_date')
            ->first();

        if ($prevIncident && $prevIncident->shift_date == $validated['shift_date']) {
            $validated['safe_day_wlta'] = 0;
        } else {
            $validated['safe_day_wlta'] = ($validated['safe_shift_wlta'] === 1) ? 1 : 0;
        }

        // === TOTAL SAFE DAY WLTA ===
        if ($validated['safe_shift_wlta'] === 0) {
            $validated['total_safe_day_wlta'] = 0;
        } else {
            $totalSafeDayWltaSebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('safe_day_wlta');

            $validated['total_safe_day_wlta'] = $validated['safe_day_wlta'] + $totalSafeDayWltaSebelumnya;
        }

        // === SAFE SHIFT WLTA ===
        $validated['safe_shift_wlta'] = $validated['wlta'] === 0 ? 1 : 0;


        $shiftOrder = [
            'Shift 1' => 1,
            'Shift 2' => 2,
            'Shift 3' => 3,
            'Nonshift' => 4,
        ];

        $currentDate = Carbon::parse($validated['shift_date']);
        $currentShift = $validated['shift'] ?? 'Shift 1';
        $currentShiftOrder = $shiftOrder[$currentShift] ?? 1;

        if ($validated['safe_shift'] == 1) {
            // Hitung total man hours sebelum laporan saat ini (berdasarkan tanggal dan urutan shift)
            $totalManHoursLtaSebelumnya = Incident::where(function ($query) use ($currentDate, $currentShiftOrder) {
                $query->where('shift_date', '<', $currentDate)
                    ->orWhere(function ($q) use ($currentDate, $currentShiftOrder) {
                        $q->where('shift_date', $currentDate)
                            ->whereRaw("CASE shift 
                WHEN 'Shift 1' THEN 1 
                WHEN 'Shift 2' THEN 2 
                WHEN 'Shift 3' THEN 3 
                WHEN 'Nonshift' THEN 4 
                ELSE 5 END < ?", [$currentShiftOrder]);
                    });
            })
                ->whereYear('shift_date', $currentDate->year)
                ->sum('man_hours_per_day');


            $validated['total_man_hours_lta'] = (int) ($validated['man_hours_per_day'] ?? 0) + $totalManHoursLtaSebelumnya;
        } else {
            $validated['total_man_hours_lta'] = 0;
        }


        // === TOTAL MAN HOURS WLTA ===
        if ($validated['safe_shift_wlta'] === 1) {
            $totalManHoursWltaSebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('man_hours_per_day');

            $validated['total_man_hours_wlta'] = $validated['man_hours_per_day'] + $totalManHoursWltaSebelumnya;
        } else {
            $validated['total_man_hours_wlta'] = 0;
        }

        if ($validated['safe_shift_wlta'] === 1) {
            // Ambil total_man_hours_wlta2 sebelumnya (kumulatif)
            $totalManHoursWlta2Sebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('total_man_hours_wlta2');

            // Tambah man_hours_per_day hari ini
            $validated['total_man_hours_wlta2'] = ($validated['man_hours_per_day'] ?? 0) + $totalManHoursWlta2Sebelumnya;
        } else {
            $validated['total_man_hours_wlta2'] = 0;
        }
        // Hitung no_laporan otomatis

        // Ambil shift_date dari request
        $shiftDate = Carbon::parse($request->shift_date);

        // Simpan ke database
        $incident = Incident::create($validated);

        return redirect()->route('operator.incident.index', $incident->id)
            ->with('success', 'Data berhasil ditambahkan.');
    }


    // Form untuk mengedit data (edit)
    public function operator_edit($id)
    {
        $incidents = Incident::findOrFail($id);
        $perusahaans = Perusahaan::all();
        $officers = HseInspector::all();
        $bagians = Bagian::all();
        $hilangs = HariHilang::all();
        return view('operator.incident.edit', compact('incidents', 'perusahaans', 'bagians', 'officers', 'hilangs'));
    }
    public function operator_sent_edit($id)
    {
        $incident_fix = SentIncident::findOrFail($id);
        $perusahaans = Perusahaan::all();
        $officers = HseInspector::all();
        $bagians = Bagian::all();
        $hilangs = HariHilang::all();
        return view('operator.incident.sent_edit', compact('incident_fix', 'perusahaans', 'bagians', 'officers', 'hilangs'));
    }

    // Memperbarui data (update)
    public function operator_update(Request $request, $id)
    {
        // Ambil data incident yang akan diupdate
        $incident = Incident::findOrFail($id);

        // Validasi awal (sesuaikan dengan kebutuhan)
        $validated = $request->validate([
            'stamp_date' => 'nullable|date_format:d/m/Y',
            'shift_date' => 'required|date',
            'shift' => 'required|string|max:255',
            'safety_officer_1' => 'required|string|max:255',
            'status_kejadian' => 'nullable|string|max:255',
            'tgl_kejadiannya' => 'nullable|date',
            'jam_kejadiannya' => 'nullable',
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
            'tindakan_kendali_untuk_peningkatan_1_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_1' => 'nullable|string',
            'pic_1' => 'nullable|string|max:255',
            'timing_1' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_b' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_2' => 'nullable|string',
            'pic_2' => 'nullable|string|max:255',
            'timing_2' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_b' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_3' => 'nullable|string',
            'pic_3' => 'nullable|string|max:255',
            'timing_3' => 'nullable|string|max:255',
            'jml_employee' => 'nullable|integer',
            'jml_outsources' => 'nullable|integer',
            'jml_security' => 'nullable|integer',
            'jml_loading_stacking' => 'nullable|integer',
            'jml_contractor' => 'nullable|integer',
            'jml_hari_hilang' => 'nullable|integer',
            'ada' => 'nullable|string',
            'no_laporan' => 'nullable|string|max:255',
        ]);

        // Tanggal stamp & user
        $validated['stamp_date'] = Carbon::today()->toDateString();
        $validated['user_id'] = auth()->user()->id;
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
        // Hitung bulan_tahun
        $validated['shift_date'] = date('Y-m-d', strtotime($request->input('shift_date')));

        // Klasifikasi Kejadian
        $klasifikasi = $request->input('klasifikasi_kejadiannya');
        // Tentukan LTA & WLTA sesuai klasifikasi
        $validated['lta'] = in_array($klasifikasi, [
            'Lost Workdays Case (LWC)',
            'Permanent Partial Disability (PPD)',
            'Permanent Total Disability (PTD)'
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
            'Permanent Partial Disability (PPD)',
            'Permanent Total Disability (PTD)'
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
        // Hitung safe_shift
        if (
            ($validated['lta'] ?? 0) === 0 &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Fire Incident' &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Road Incident'
        ) {
            $validated['safe_shift'] = 1;
        } else {
            $validated['safe_shift'] = 0;
        }

        // Hitung safe_day
        if (
            ($validated['lta'] ?? 0) === 0 &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Fire Incident'
        ) {
            $validated['safe_day'] = 1;
        } else {
            $validated['safe_day'] = 0;
        }


        // PENGHITUNGAN TOTAL SAFE DAY BY YEAR
        try {
            $shiftDate = $request->input('shift_date'); // contoh: 2025-01-01
            $shift = $request->input('shift');
            $safeDay = $validated['safe_day']; // input hari ini
            $safeShift = $validated['safe_shift']; // input hari ini, 1 atau 0

            // Jika shift_date adalah "01 JAN" dan shift adalah "SHIFT I"
            $isFirstDay = (date('d M', strtotime($shiftDate)) === '01 Jan') && ($shift === 'SHIFT I');

            if ($isFirstDay) {
                $validated['total_safe_day_by_year'] = $safeDay;
            } elseif ($safeShift == 1) {
                // Hitung jumlah total safe_day sebelumnya (di tahun yang sama)
                $totalSafeDaySebelumnya = Incident::whereYear('shift_date', date('Y', strtotime($shiftDate)))
                    ->where('shift_date', '<', $shiftDate)
                    ->sum('safe_day');

                $validated['total_safe_day_by_year'] = $safeDay + $totalSafeDaySebelumnya;
            } else {
                $validated['total_safe_day_by_year'] = 0;
            }
        } catch (\Exception $e) {
            // Jika ada error apapun, fallback ke 0
            $validated['total_safe_day_by_year'] = 0;
        }
        // PERHITUNGAN TOTAL SAFE DAY LTA 2
        try {
            $safeShift = $validated['safe_shift']; // 1 atau 0
            $safeDay = $validated['safe_day'];
            $shiftDate = $request->input('shift_date');

            if (!$safeShift) {
                $validated['total_safe_day_lta2'] = 0;
            } else {
                $totalSafeDayLTA2Sebelumnya = Incident::where('shift_date', '<', $shiftDate)
                    ->whereYear('shift_date', date('Y', strtotime($shiftDate)))
                    ->sum('safe_day');

                $validated['total_safe_day_lta2'] = $safeDay + $totalSafeDayLTA2Sebelumnya;
            }
        } catch (\Exception $e) {
            $validated['total_safe_day_lta2'] = 0;
        }
        // === SAFE SHIFT WLTA ===
        // Jika WLTA = 0, maka aman
        $validated['safe_shift_wlta'] = ($validated['wlta'] == 0) ? 1 : 0;

        // === SAFE DAY WLTA ===
        // Bandingkan dengan tanggal sebelumnya (asumsi berdasarkan shift_date dan shift)
        $prevIncident = Incident::where('shift_date', '<', $validated['shift_date'])
            ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
            ->orderByDesc('shift_date')
            ->first();

        if ($prevIncident && $prevIncident->shift_date == $validated['shift_date']) {
            $validated['safe_day_wlta'] = 0;
        } else {
            $validated['safe_day_wlta'] = ($validated['safe_shift_wlta'] === 1) ? 1 : 0;
        }

        // === TOTAL SAFE DAY WLTA ===
        if ($validated['safe_shift_wlta'] === 0) {
            $validated['total_safe_day_wlta'] = 0;
        } else {
            $totalSafeDayWltaSebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('safe_day_wlta');

            $validated['total_safe_day_wlta'] = $validated['safe_day_wlta'] + $totalSafeDayWltaSebelumnya;
        }

        // === SAFE SHIFT WLTA ===
        $validated['safe_shift_wlta'] = $validated['wlta'] === 0 ? 1 : 0;


        $shiftOrder = [
            'Shift 1' => 1,
            'Shift 2' => 2,
            'Shift 3' => 3,
            'Nonshift' => 4,
        ];

        $currentDate = Carbon::parse($validated['shift_date']);
        $currentShift = $validated['shift'] ?? 'Shift 1';
        $currentShiftOrder = $shiftOrder[$currentShift] ?? 1;

        if ($validated['safe_shift'] == 1) {
            // Hitung total man hours sebelum laporan saat ini (berdasarkan tanggal dan urutan shift)
            $totalManHoursLtaSebelumnya = Incident::where(function ($query) use ($currentDate, $currentShiftOrder) {
                $query->where('shift_date', '<', $currentDate)
                    ->orWhere(function ($q) use ($currentDate, $currentShiftOrder) {
                        $q->where('shift_date', $currentDate)
                            ->whereRaw("CASE shift 
                WHEN 'Shift 1' THEN 1 
                WHEN 'Shift 2' THEN 2 
                WHEN 'Shift 3' THEN 3 
                WHEN 'Nonshift' THEN 4 
                ELSE 5 END < ?", [$currentShiftOrder]);
                    });
            })
                ->whereYear('shift_date', $currentDate->year)
                ->sum('man_hours_per_day');


            $validated['total_man_hours_lta'] = (int) ($validated['man_hours_per_day'] ?? 0) + $totalManHoursLtaSebelumnya;
        } else {
            $validated['total_man_hours_lta'] = 0;
        }


        // === TOTAL MAN HOURS WLTA ===
        if ($validated['safe_shift_wlta'] === 1) {
            $totalManHoursWltaSebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('man_hours_per_day');

            $validated['total_man_hours_wlta'] = $validated['man_hours_per_day'] + $totalManHoursWltaSebelumnya;
        } else {
            $validated['total_man_hours_wlta'] = 0;
        }

        if ($validated['safe_shift_wlta'] === 1) {
            // Ambil total_man_hours_wlta2 sebelumnya (kumulatif)
            $totalManHoursWlta2Sebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('total_man_hours_wlta2');

            // Tambah man_hours_per_day hari ini
            $validated['total_man_hours_wlta2'] = ($validated['man_hours_per_day'] ?? 0) + $totalManHoursWlta2Sebelumnya;
        } else {
            $validated['total_man_hours_wlta2'] = 0;
        }
        // Hitung no_laporan otomatis

        // Ambil shift_date dari request
        $shiftDate = Carbon::parse($request->shift_date);

        // Hitung jumlah kejadian pada bulan & tahun yang sama
        $incidentCount = SentIncident::whereMonth('shift_date', $shiftDate->month)
            ->whereYear('shift_date', $shiftDate->year)
            ->count() + 1;

        // Mapping bulan ke angka Romawi
        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        // Ambil angka romawi berdasarkan bulan dari shiftDate
        $bulan = $bulanRomawi[(int)$shiftDate->format('n')];

        // Tahun format 2 digit
        $tahun2Digit = $shiftDate->format('y');

        // Susun no_laporan akhir
        unset($validated['no_laporan']);

        // Update semua nilai ke dalam model

        $incident->update($validated);

        return redirect()->route('operator.incident.index')
            ->with('success', 'Data berhasil diperbarui.');
    }
    public function operator_sent_update(Request $request, $id)
    {
        // Ambil data incident yang akan diupdate
        $incident = SentIncident::findOrFail($id);

        // Validasi awal (sesuaikan dengan kebutuhan)
        $validated = $request->validate([
            'stamp_date' => 'nullable|date_format:d/m/Y',
            'shift_date' => 'required|date',
            'shift' => 'required|string|max:255',
            'safety_officer_1' => 'required|string|max:255',
            'status_kejadian' => 'nullable|string|max:255',
            'tgl_kejadiannya' => 'nullable|date',
            'jam_kejadiannya' => 'nullable',
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
            'tindakan_kendali_untuk_peningkatan_1_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_1' => 'nullable|string',
            'pic_1' => 'nullable|string|max:255',
            'timing_1' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_b' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_2_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_2' => 'nullable|string',
            'pic_2' => 'nullable|string|max:255',
            'timing_2' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_a' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_b' => 'nullable|string|max:255',
            'tindakan_kendali_untuk_peningkatan_3_c' => 'nullable|string|max:255',
            'deskripsi_tindakan_pencegahan_3' => 'nullable|string',
            'pic_3' => 'nullable|string|max:255',
            'timing_3' => 'nullable|string|max:255',
            'jml_employee' => 'nullable|integer',
            'jml_outsources' => 'nullable|integer',
            'jml_security' => 'nullable|integer',
            'jml_loading_stacking' => 'nullable|integer',
            'jml_contractor' => 'nullable|integer',
            'jml_hari_hilang' => 'nullable|integer',
            'ada' => 'nullable|string',
            'no_laporan' => 'nullable|string|max:255',
        ]);

        // Tanggal stamp & user
        $validated['stamp_date'] = Carbon::today()->toDateString();
        $validated['user_id'] = auth()->user()->id;
        $validated['writer'] = auth()->user()->name;
        $validated['status_request'] = "Nothing";

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
        // Hitung bulan_tahun
        $validated['shift_date'] = date('Y-m-d', strtotime($request->input('shift_date')));

        // Klasifikasi Kejadian
        $klasifikasi = $request->input('klasifikasi_kejadiannya');
        // Tentukan LTA & WLTA sesuai klasifikasi
        $validated['lta'] = in_array($klasifikasi, [
            'Lost Workdays Case (LWC)',
            'Permanent Partial Disability (PPD)',
            'Permanent Total Disability (PTD)'
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
            'Permanent Partial Disability (PPD)',
            'Permanent Total Disability (PTD)'
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
        // Hitung safe_shift
        if (
            ($validated['lta'] ?? 0) === 0 &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Fire Incident' &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Road Incident'
        ) {
            $validated['safe_shift'] = 1;
        } else {
            $validated['safe_shift'] = 0;
        }

        // Hitung safe_day
        if (
            ($validated['lta'] ?? 0) === 0 &&
            ($validated['klasifikasi_kejadiannya'] ?? '') !== 'Fire Incident'
        ) {
            $validated['safe_day'] = 1;
        } else {
            $validated['safe_day'] = 0;
        }


        // PENGHITUNGAN TOTAL SAFE DAY BY YEAR
        try {
            $shiftDate = $request->input('shift_date'); // contoh: 2025-01-01
            $shift = $request->input('shift');
            $safeDay = $validated['safe_day']; // input hari ini
            $safeShift = $validated['safe_shift']; // input hari ini, 1 atau 0

            // Jika shift_date adalah "01 JAN" dan shift adalah "SHIFT I"
            $isFirstDay = (date('d M', strtotime($shiftDate)) === '01 Jan') && ($shift === 'SHIFT I');

            if ($isFirstDay) {
                $validated['total_safe_day_by_year'] = $safeDay;
            } elseif ($safeShift == 1) {
                // Hitung jumlah total safe_day sebelumnya (di tahun yang sama)
                $totalSafeDaySebelumnya = Incident::whereYear('shift_date', date('Y', strtotime($shiftDate)))
                    ->where('shift_date', '<', $shiftDate)
                    ->sum('safe_day');

                $validated['total_safe_day_by_year'] = $safeDay + $totalSafeDaySebelumnya;
            } else {
                $validated['total_safe_day_by_year'] = 0;
            }
        } catch (\Exception $e) {
            // Jika ada error apapun, fallback ke 0
            $validated['total_safe_day_by_year'] = 0;
        }
        // PERHITUNGAN TOTAL SAFE DAY LTA 2
        try {
            $safeShift = $validated['safe_shift']; // 1 atau 0
            $safeDay = $validated['safe_day'];
            $shiftDate = $request->input('shift_date');

            if (!$safeShift) {
                $validated['total_safe_day_lta2'] = 0;
            } else {
                $totalSafeDayLTA2Sebelumnya = Incident::where('shift_date', '<', $shiftDate)
                    ->whereYear('shift_date', date('Y', strtotime($shiftDate)))
                    ->sum('safe_day');

                $validated['total_safe_day_lta2'] = $safeDay + $totalSafeDayLTA2Sebelumnya;
            }
        } catch (\Exception $e) {
            $validated['total_safe_day_lta2'] = 0;
        }
        // === SAFE SHIFT WLTA ===
        // Jika WLTA = 0, maka aman
        $validated['safe_shift_wlta'] = ($validated['wlta'] == 0) ? 1 : 0;

        // === SAFE DAY WLTA ===
        // Bandingkan dengan tanggal sebelumnya (asumsi berdasarkan shift_date dan shift)
        $prevIncident = Incident::where('shift_date', '<', $validated['shift_date'])
            ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
            ->orderByDesc('shift_date')
            ->first();

        if ($prevIncident && $prevIncident->shift_date == $validated['shift_date']) {
            $validated['safe_day_wlta'] = 0;
        } else {
            $validated['safe_day_wlta'] = ($validated['safe_shift_wlta'] === 1) ? 1 : 0;
        }

        // === TOTAL SAFE DAY WLTA ===
        if ($validated['safe_shift_wlta'] === 0) {
            $validated['total_safe_day_wlta'] = 0;
        } else {
            $totalSafeDayWltaSebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('safe_day_wlta');

            $validated['total_safe_day_wlta'] = $validated['safe_day_wlta'] + $totalSafeDayWltaSebelumnya;
        }

        // === SAFE SHIFT WLTA ===
        $validated['safe_shift_wlta'] = $validated['wlta'] === 0 ? 1 : 0;


        $shiftOrder = [
            'Shift 1' => 1,
            'Shift 2' => 2,
            'Shift 3' => 3,
            'Nonshift' => 4,
        ];

        $currentDate = Carbon::parse($validated['shift_date']);
        $currentShift = $validated['shift'] ?? 'Shift 1';
        $currentShiftOrder = $shiftOrder[$currentShift] ?? 1;

        if ($validated['safe_shift'] == 1) {
            // Hitung total man hours sebelum laporan saat ini (berdasarkan tanggal dan urutan shift)
            $totalManHoursLtaSebelumnya = Incident::where(function ($query) use ($currentDate, $currentShiftOrder) {
                $query->where('shift_date', '<', $currentDate)
                    ->orWhere(function ($q) use ($currentDate, $currentShiftOrder) {
                        $q->where('shift_date', $currentDate)
                            ->whereRaw("CASE shift 
                WHEN 'Shift 1' THEN 1 
                WHEN 'Shift 2' THEN 2 
                WHEN 'Shift 3' THEN 3 
                WHEN 'Nonshift' THEN 4 
                ELSE 5 END < ?", [$currentShiftOrder]);
                    });
            })
                ->whereYear('shift_date', $currentDate->year)
                ->sum('man_hours_per_day');


            $validated['total_man_hours_lta'] = (int) ($validated['man_hours_per_day'] ?? 0) + $totalManHoursLtaSebelumnya;
        } else {
            $validated['total_man_hours_lta'] = 0;
        }


        // === TOTAL MAN HOURS WLTA ===
        if ($validated['safe_shift_wlta'] === 1) {
            $totalManHoursWltaSebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('man_hours_per_day');

            $validated['total_man_hours_wlta'] = $validated['man_hours_per_day'] + $totalManHoursWltaSebelumnya;
        } else {
            $validated['total_man_hours_wlta'] = 0;
        }

        if ($validated['safe_shift_wlta'] === 1) {
            // Ambil total_man_hours_wlta2 sebelumnya (kumulatif)
            $totalManHoursWlta2Sebelumnya = Incident::where('shift_date', '<', $validated['shift_date'])
                ->whereYear('shift_date', date('Y', strtotime($validated['shift_date'])))
                ->sum('total_man_hours_wlta2');

            // Tambah man_hours_per_day hari ini
            $validated['total_man_hours_wlta2'] = ($validated['man_hours_per_day'] ?? 0) + $totalManHoursWlta2Sebelumnya;
        } else {
            $validated['total_man_hours_wlta2'] = 0;
        }
        // Hitung no_laporan otomatis

        // Ambil shift_date dari request
        $shiftDate = Carbon::parse($request->shift_date);

        // Hitung jumlah kejadian pada bulan & tahun yang sama
        $incidentCount = SentIncident::whereMonth('shift_date', $shiftDate->month)
            ->whereYear('shift_date', $shiftDate->year)
            ->count() + 1;

        unset($validated['no_laporan']);

        // Update semua nilai ke dalam model

        $incident->update($validated);

        return redirect()->route('operator.incident.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    // Melihat detail data (show)
    public function operator_show($id)
    {
        $incident = Incident::find($id);

        if (!$incident) {
            abort(404, 'Data tidak ditemukan');
        }
        return view('operator.incident.show ', compact('incident'));
    }
    public function operator_sent_show($id)
    {
        $incident = SentIncident::find($id);

        if (!$incident) {
            abort(404, 'Data tidak ditemukan');
        }
        return view('operator.incident.view ', compact('incident'));
    }

    // Menghapus data (destroy)
    public function operator_destroy($id)
    {
        $incident = Incident::findOrFail($id);
        $shiftDate = $incident->shift_date;
        // Hitung jumlah kejadian pada bulan & tahun yang sama
        $incidentCount = SentIncident::whereMonth('shift_date', $shiftDate->month)
            ->whereYear('shift_date', $shiftDate->year)
            ->count() + 1;

        // Mapping bulan ke angka Romawi
        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        // Ambil angka romawi berdasarkan bulan dari shiftDate
        $bulan = $bulanRomawi[(int)$shiftDate->format('n')];

        // Tahun format 2 digit
        $tahun2Digit = $shiftDate->format('y');

        // Susun no_laporan akhir
        $no_laporan = "{$incidentCount}/{$bulan}/HSE/{$tahun2Digit}";
        // Pindahkan data ke tabel incident_fix
        SentIncident::create([
            'writer' => $incident->writer,
            'user_id' => $incident->user_id,
            'stamp_date' => $incident->stamp_date,
            'shift_date' => $incident->shift_date,
            'shift' => $incident->shift,
            'safety_officer_1' => $incident->safety_officer_1,
            'status_kejadian' => $incident->status_kejadian,
            'tgl_kejadiannya' => $incident->tgl_kejadiannya,
            'jam_kejadiannya' => $incident->jam_kejadiannya,
            'lokasi_kejadiannya' => $incident->lokasi_kejadiannya,
            'klasifikasi_kejadiannya' => $incident->klasifikasi_kejadiannya,
            'ada_korban' => $incident->ada_korban,
            'nama_korban' => $incident->nama_korban,
            'status' => $incident->status,
            'jenis_kelamin' => $incident->jenis_kelamin,
            'perusahaan' => $incident->perusahaan,
            'bagian' => $incident->bagian,
            'jabatan' => $incident->jabatan,
            'masa_kerja' => $incident->masa_kerja,
            'tgl_lahir' => $incident->tgl_lahir,
            'jenis_luka_sakit' => $incident->jenis_luka_sakit,
            'jenis_luka_sakit2' => $incident->jenis_luka_sakit2,
            'jenis_luka_sakit3' => $incident->jenis_luka_sakit3,
            'bagian_tubuh_luka' => $incident->bagian_tubuh_luka,
            'bagian_tubuh_luka2' => $incident->bagian_tubuh_luka2,
            'bagian_tubuh_luka3' => $incident->bagian_tubuh_luka3,
            'jenis_kejadiannya' => $incident->jenis_kejadiannya,
            'penjelasan_kejadiannya' => $incident->penjelasan_kejadiannya,
            'tindakan_pengobatan' => $incident->tindakan_pengobatan,
            'tindakan_segera_yang_dilakukan' => $incident->tindakan_segera_yang_dilakukan,
            'rincian_dari_pemeriksaan' => $incident->rincian_dari_pemeriksaan,
            'penyebab_langsung_1_a' => $incident->penyebab_langsung_1_a,
            'penyebab_langsung_1_b' => $incident->penyebab_langsung_1_b,
            'penyebab_langsung_2_a' => $incident->penyebab_langsung_2_a,
            'penyebab_langsung_2_b' => $incident->penyebab_langsung_2_b,
            'penyebab_langsung_3_a' => $incident->penyebab_langsung_3_a,
            'penyebab_langsung_3_b' => $incident->penyebab_langsung_3_b,
            'penyebab_dasar_1_a' => $incident->penyebab_dasar_1_a,
            'penyebab_dasar_1_b' => $incident->penyebab_dasar_1_b,
            'penyebab_dasar_1_c' => $incident->penyebab_dasar_1_c,
            'penyebab_dasar_2_a' => $incident->penyebab_dasar_2_a,
            'penyebab_dasar_2_b' => $incident->penyebab_dasar_2_b,
            'penyebab_dasar_2_c' => $incident->penyebab_dasar_2_c,
            'penyebab_dasar_3_a' => $incident->penyebab_dasar_3_a,
            'penyebab_dasar_3_b' => $incident->penyebab_dasar_3_b,
            'penyebab_dasar_3_c' => $incident->penyebab_dasar_3_c,
            'tindakan_kendali_untuk_peningkatan_1_a' => $incident->tindakan_kendali_untuk_peningkatan_1_a,
            'tindakan_kendali_untuk_peningkatan_1_b' => $incident->tindakan_kendali_untuk_peningkatan_1_b,
            'tindakan_kendali_untuk_peningkatan_1_c' => $incident->tindakan_kendali_untuk_peningkatan_1_c,
            'deskripsi_tindakan_pencegahan_1' => $incident->deskripsi_tindakan_pencegahan_1,
            'pic_1' => $incident->pic_1,
            'timing_1' => $incident->timing_1,
            'tindakan_kendali_untuk_peningkatan_2_a' => $incident->tindakan_kendali_untuk_peningkatan_2_a,
            'tindakan_kendali_untuk_peningkatan_2_b' => $incident->tindakan_kendali_untuk_peningkatan_2_b,
            'tindakan_kendali_untuk_peningkatan_2_c' => $incident->tindakan_kendali_untuk_peningkatan_2_c,
            'deskripsi_tindakan_pencegahan_2' => $incident->deskripsi_tindakan_pencegahan_2,
            'pic_2' => $incident->pic_2,
            'timing_2' => $incident->timing_2,
            'tindakan_kendali_untuk_peningkatan_3_a' => $incident->tindakan_kendali_untuk_peningkatan_3_a,
            'tindakan_kendali_untuk_peningkatan_3_b' => $incident->tindakan_kendali_untuk_peningkatan_3_b,
            'tindakan_kendali_untuk_peningkatan_3_c' => $incident->tindakan_kendali_untuk_peningkatan_3_c,
            'deskripsi_tindakan_pencegahan_3' => $incident->deskripsi_tindakan_pencegahan_3,
            'pic_3' => $incident->pic_3,
            'timing_3' => $incident->timing_3,
            'jml_employee' => $incident->jml_employee,
            'jml_outsources' => $incident->jml_outsources,
            'jml_security' => $incident->jml_security,
            'jml_loading_stacking' => $incident->jml_loading_stacking,
            'jml_contractor' => $incident->jml_contractor,
            'jml_hari_hilang' => $incident->jml_hari_hilang,
            'ada' => $incident->ada,
            'lta' => $incident->lta,
            'wlta' => $incident->wlta,
            'trc' => $incident->trc,
            'fire_incident' => $incident->fire_incident,
            'road_incident' => $incident->road_incident,
            'property_loss_damage' => $incident->property_loss_damage,
            'environmental_incident' => $incident->environmental_incident,
            'total_lta_by_year' => $incident->total_lta_by_year,
            'total_wlta_by_year' => $incident->total_wlta_by_year,
            'total_work_force' => $incident->total_work_force,
            'man_hours_per_day' => $incident->man_hours_per_day,
            'total_man_hours' => $incident->total_man_hours,
            'safe_shift' => $incident->safe_shift,
            'safe_day' => $incident->safe_day,
            'total_safe_day_by_year' => $incident->total_safe_day_by_year,
            'total_safe_day_lta2' => $incident->total_safe_day_lta2,
            'total_man_hours_lta' => $incident->total_man_hours_lta,
            'total_man_hours_wlta2' => $incident->total_man_hours_wlta2,
            'safe_shift_wlta' => $incident->safe_shift_wlta,
            'safe_day_wlta' => $incident->safe_day_wlta,
            'total_safe_day_wlta' => $incident->total_safe_day_wlta,
            'foto' => $incident->foto,
            'status_request' => 'Nothing',
            'draft_id' => $incident->id,
            'no_laporan' => $no_laporan,
        ]);


        // Hapus data dari incident
        $incident->delete();
        // Redirect dengan notifikasi
        return redirect()->route('operator.incident.index')->with('notification', 'Laporan berhasil dikirim!');
    }
    public function operator_draft_destroy($id)
    {
        $incident = Incident::findOrFail($id);

        // Delete the incident record
        $incident->delete();

        // Redirect to a relevant page, such as the incident index page with a success message
        return redirect()->route('operator.incident.index')->with('success', 'Incident deleted successfully.');
    }
    public function operator_sent_destroy($id)
    {
        $incident = SentIncident::findOrFail($id);

        // Delete the incident record
        $incident->delete();

        // Redirect to a relevant page, such as the incident index page with a success message
        return redirect()->route('operator.incident.index')->with('success', 'Incident deleted successfully.');
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
            'user_id' => Auth::user()->id,
            'status' => 'Pending',
        ]);

        SentIncident::where('id', $request->sent_incident_id)->update([
            'status_request' => 'Pending',
        ]);

        // Kirim email ke semua operator
        $admins = User::where('role', 'operator')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new IncidentRequestNotification($incidentRequest));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Request berhasil dikirim dan email telah dikirim ke admin.',
            ]);
        }

        return redirect()->route('operator.incident.index')->with('success', 'Request berhasil dikirim dan email telah dikirim ke admin.');
    }


    public function operator_getBagian($perusahaan_name)
    {
        $bagians = Bagian::where('perusahaan_name', $perusahaan_name)->get();
        return response()->json($bagians);
    }
    public function operator_master()
    {
        $pers = Perusahaan::all();
        $bagians = Bagian::all();
        return view('operator.incident.master', compact('pers', 'bagians'));
    }
    public function operator_export(Request $request)
    {
        return Excel::download(new IncidentExport($request->start_date, $request->end_date), 'incident_report.xlsx');
    }
    public function operator_exportPdf(Request $request)
    {
        $incidents = SentIncident::whereBetween('shift_date', [$request->start_date, $request->end_date])->get();

        $pdf = Pdf::loadView('operator.incident.pdf', compact('incidents'));
        return $pdf->download('incident_report.pdf');
    }
    public function operator_getJumlahHariHilang(Request $request)
    {
        $total = 0;

        foreach (['jenis1', 'jenis2', 'jenis3'] as $field) {
            $jenis = $request->input($field);
            if ($jenis) {
                $nilai = DB::table('jumlah_hari_hilang')
                    ->where('jenis_luka', $jenis)
                    ->value('jml_hari_hilang');
                $total += (int) ($nilai ?? 0);
            }
        }

        return response()->json(['total' => $total]);
    }
    public function operator_downloadSatuan($id)
    {
        $incident = SentIncident::findOrFail($id);
        $pdf = Pdf::loadView('operator.incident.view', compact('incident'));
        return $pdf->download('Incident_Report_' . $incident->id . '.pdf');
    }
}
