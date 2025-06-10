<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Budget;
use App\Models\BudgetFix;
use App\Models\Note;
use App\Models\SentIncident;
use App\Models\SentNcr;
use App\Models\SentPpe;
use App\Models\Target;
use App\Models\TargetManHours;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        // Dapatkan peran pengguna yang sedang login
        $role = Auth::user()->role;
        $notes = Note::with('attachments')->get();

        // Arahkan ke halaman sesuai peran
        if ($role === 'adminsystem') {
            return $this->adminsystem($notes);
        } elseif ($role === 'operator') {
            return $this->operator($notes);
        } elseif ($role === 'tamu') {
            return $this->tamu($notes);
        }

        abort(403, 'Unauthorized access');
    }

    private function adminsystem($notes)
    {
        return view('adminsystem.home', compact('notes'));
    }

    private function operator($notes)
    {
        return view('operator.home', compact('notes'));
    }
    private function tamu($notes)
    {
        return view('tamu.home', compact('notes'));
    }
    //  NOTES
    public function store(Request $request)
    {
        $request->validate([
            'note' => 'required',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048', // Adjust file types and size as needed
        ]);

        $note = Note::create([
            'user_id' => auth()->user()->id, // Assuming you are using authentication
            'writer' => auth()->user()->name, // Assuming you are using authentication
            'note' => $request->input('note'),
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public'); // Store in 'storage/app/public/uploads'

            Attachment::create([
                'note_id' => $note->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
            ]);
        }

        return redirect()->route('adminsystem.home')->with('success', 'Catatan berhasil disimpan.');
    }
    public function destroy(Note $note)
    {
        foreach ($note->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
        }

        $note->delete();

        return redirect()->route('adminsystem.home')->with('success', 'Catatan dan attachment berhasil dihapus.');
    }


    public function dashboard(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $query = DB::table('ppe_fix');

        if ($year) {
            $query->whereYear('tanggal_shift_kerja', $year);
        }

        if ($month) {
            $query->whereMonth('tanggal_shift_kerja', $month);
            $groupBy = DB::raw('DATE(tanggal_shift_kerja)');
            $selectDate = "DATE(tanggal_shift_kerja) as tanggal";
        } else {
            $groupBy = DB::raw('MONTH(tanggal_shift_kerja)');
            $selectDate = "MONTH(tanggal_shift_kerja) as bulan";
        }

        $data = $query->selectRaw("
        $selectDate,
        SUM(jumlah_patuh_apd_karyawan) as patuh_karyawan,
        (
            SUM(jumlah_tidak_patuh_helm_karyawan) +
            SUM(jumlah_tidak_patuh_sepatu_karyawan) +
            SUM(jumlah_tidak_patuh_pelindung_mata_karyawan) +
            SUM(jumlah_tidak_patuh_safety_harness_karyawan) +
            SUM(jumlah_tidak_patuh_apd_lainnya_karyawan)
        ) as tidak_patuh_karyawan,
        SUM(jumlah_patuh_apd_kontraktor) as patuh_kontraktor,
        (
            SUM(jumlah_tidak_patuh_helm_kontraktor) +
            SUM(jumlah_tidak_patuh_sepatu_kontraktor) +
            SUM(jumlah_tidak_patuh_pelindung_mata_kontraktor) +
            SUM(jumlah_tidak_patuh_safety_harness_kontraktor) +
            SUM(jumlah_tidak_patuh_apd_lainnya_kontraktor)
        ) as tidak_patuh_kontraktor,
        SUM(jumlah_tidak_patuh_helm_karyawan) as tidak_patuh_helm_karyawan,
        SUM(jumlah_tidak_patuh_sepatu_karyawan) as tidak_patuh_sepatu_karyawan,
        SUM(jumlah_tidak_patuh_helm_kontraktor) as tidak_patuh_helm_kontraktor,
        SUM(jumlah_tidak_patuh_sepatu_kontraktor) as tidak_patuh_sepatu_kontraktor
    ")
            ->groupBy($groupBy)
            ->orderBy($groupBy)
            ->get();

        $labels = [];
        $employeeData = [];
        $contractorData = [];
        $tidak_patuh_karyawan = [];
        $patuh_karyawan = [];
        $tidak_patuh_kontraktor = [];
        $patuh_kontraktor = [];
        $employeeHelmData = [];
        $employeeSepatuData = [];
        $contractorHelmData = [];
        $contractorSepatuData = [];

        foreach ($data as $item) {
            if ($month) {
                $labels[] = date('d M', strtotime($item->tanggal));
            } else {
                $labels[] = date('M', mktime(0, 0, 0, $item->bulan, 10));
            }

            $total_karyawan = $item->patuh_karyawan + $item->tidak_patuh_karyawan;
            $total_kontraktor = $item->patuh_kontraktor + $item->tidak_patuh_kontraktor;

            $employeeData[] = $total_karyawan > 0 ? round(($item->patuh_karyawan / $total_karyawan) * 100, 2) : 0;
            $contractorData[] = $total_kontraktor > 0 ? round(($item->patuh_kontraktor / $total_kontraktor) * 100, 2) : 0;

            $tidak_patuh_karyawan[] = $item->tidak_patuh_karyawan;
            $patuh_karyawan[] = $item->patuh_karyawan;
            $tidak_patuh_kontraktor[] = $item->tidak_patuh_kontraktor;
            $patuh_kontraktor[] = $item->patuh_kontraktor;

            $employeeHelmData[] = $item->tidak_patuh_helm_karyawan;
            $employeeSepatuData[] = $item->tidak_patuh_sepatu_karyawan;
            $contractorHelmData[] = $item->tidak_patuh_helm_kontraktor;
            $contractorSepatuData[] = $item->tidak_patuh_sepatu_kontraktor;
        }

        $years = DB::table('ppe_fix')->selectRaw('YEAR(tanggal_shift_kerja) as year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $months = DB::table('ppe_fix')->selectRaw('MONTH(tanggal_shift_kerja) as month')->distinct()->orderBy('month', 'desc')->pluck('month');

        $targetEmployee = Target::where('key', 'target_employee_compliance')->value('value');
        $targetContractor = Target::where('key', 'target_contractor_compliance')->value('value');

        return view('adminsystem.dashboard.dashboard', compact(
            'labels',
            'employeeData',
            'contractorData',
            'years',
            'months',
            'year',
            'month',
            'targetEmployee',
            'targetContractor',
            'tidak_patuh_kontraktor',
            'patuh_kontraktor',
            'tidak_patuh_karyawan',
            'patuh_karyawan',
            'employeeHelmData',
            'employeeSepatuData',
            'contractorHelmData',
            'contractorSepatuData'
        ));
    }



    public function budget()
    {
        $budget_fixs = BudgetFix::all();
        return view('adminsystem.dashboard.budget', compact('budget_fixs'));
    }
    public function ncr(Request $request)
    {
        $year = $request->input('year');

        $ncr_fixs = SentNcr::when($year, function ($query) use ($year) {
            return $query->whereYear('tanggal_shift_kerja', $year);
        })->get();

        $openCount = $ncr_fixs->where('status_ncr', 'Open')->count();
        $closedCount = $ncr_fixs->where('status_ncr', 'Closed')->count();
        $years = SentNcr::selectRaw('YEAR(tanggal_shift_kerja) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        return view('adminsystem.dashboard.ncr', compact('ncr_fixs', 'openCount', 'closedCount', 'years', 'year'));
    }

    public function setComplianceTarget(Request $request)
    {
        $request->validate([
            'type' => 'required|in:employee,contractor',
            'target' => 'required|numeric|min:0|max:100',
        ]);

        $type = $request->type;
        $target = $request->target;

        // Simpan ke database atau cache (tergantung implementasi kamu)
        Target::updateOrCreate(
            ['key' => "target_{$type}_compliance"],
            ['value' => $target]
        );

        return redirect()->back()->with('success', 'Target compliance berhasil diperbarui.');
    }


    public function incident(Request $request)
    {
        // Default hari ini jika tidak diisi
        $shiftDate = $request->input('shift_date')
            ? Carbon::parse($request->input('shift_date'))->format('Y-m-d')
            : now()->format('Y-m-d');
        $shift = $request->input('shift');

        if ($request->has(['shift_date', 'shift'])) {
            $request->validate([
                'shift_date' => 'required|date',
                'shift' => 'required|string',
            ]);
        }

        if (!$shift) {
            return view('adminsystem.dashboard.dashboard-incident', [
                'daysSinceLastLTA' => 0,
                'daysSinceLastWLTA' => 0,
                'manHoursSinceLastLTA' => 0,
                'manHoursSinceLastWLTA' => 0,
                'totalLTA' => 0,
                'totalWLTA' => 0,
                'totalManHours' => 0,
                'lastLTAIncidentDate' => null,
                'selectedDate' => $shiftDate,
                'targetManHours' => 0,
            ]);
        }
        $targetManHours = TargetManHours::value('target_manhours');
        $data = SentIncident::whereDate('shift_date', $shiftDate)
            ->where('shift', $shift)
            ->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk tanggal dan shift tersebut.');
        }

        $lastLtaDate = SentIncident::where('lta', 1)
            ->whereDate('shift_date', '<=', $shiftDate)
            ->orderByDesc('shift_date')
            ->value('shift_date');

        return view('adminsystem.dashboard.dashboard-incident', [
            'daysSinceLastLTA' => $data->total_safe_day_lta2 ?? 0,
            'daysSinceLastWLTA' => $data->total_safe_day_wlta ?? 0,
            'manHoursSinceLastLTA' => $data->total_man_hours_lta ?? 0,
            'manHoursSinceLastWLTA' => $data->total_man_hours_wlta2 ?? 0,
            'totalLTA' => $data->total_lta_by_year ?? 0,
            'totalWLTA' => $data->total_wlta_by_year ?? 0,
            'totalManHours' => $data->total_man_hours ?? 0,
            'lastLTAIncidentDate' => $lastLtaDate,
            'selectedDate' => $shiftDate,
            'targetManHours' => $targetManHours,
        ]);
    }



    public function updateTargetManHours(Request $request)
    {
        $request->validate([
            'target_manhours' => 'required|integer|min:0',
        ]);

        // Misalnya hanya satu row (single config row)
        $setting = TargetManHours::first();
        if (!$setting) {
            $setting = new TargetManHours();
        }

        $setting->target_manhours = $request->target_manhours;
        $setting->save();

        return redirect()->back()->with('success', 'Target updated successfully.');
    }

    public function spi(Request $request)
    {
        $data = [];

        // Loop per bulan dari Jan (1) sampai Dec (12)
        $year = $request->input('year', date('Y')); // default tahun ini

        $spiData = [];

        for ($month = 1; $month <= 12; $month++) {
            $incidents = DB::table('incidents_fix')
                ->whereMonth('shift_date', $month)
                ->whereYear('shift_date', $year)
                ->get();

            $data[] = [
                'month' => Carbon::create()->month($month)->format('M'),
                'lta' => $incidents->where('lost_workdays_case', true)->count(),
                'wlta' => $incidents->whereIn('klasifikasi_kejadiannya', [
                    'First Aid',
                    'Medical Treatment Case (MTC)',
                    'Restricted Work Case (RWC)'
                ])->count(),

                'ltifr' => $incidents->sum('jumlah_hari_hilang'),
                'man_hours' => $incidents->sum('total_man_hours'),
                'near_miss' => $incidents->where('klasifikasi_kejadiannya', 'Near Miss')->count(),
                'illness_sick' => $incidents->where('klasifikasi_kejadiannya', 'Illness/Sick')->count(),
                'first_aid_case' => $incidents->where('klasifikasi_kejadiannya', 'First Aid')->count(),
                'medical_treatment_case' => $incidents->where('klasifikasi_kejadiannya', 'Medical Treatment Case (MTC)')->count(),
                'restricted_work_case' => $incidents->where('klasifikasi_kejadiannya', 'Restricted Work Case (RWC)')->count(),
                'lost_workdays_case' => $incidents->where('klasifikasi_kejadiannya', 'Lost Workdays Case (LWC)')->count(),
                'permanent_partial_disability' => $incidents->where('klasifikasi_kejadiannya', 'Permanent Partial Disability (PPD)')->count(),
                'permanent_total_disability' => $incidents->where('klasifikasi_kejadiannya', 'Permanent Total Disability (PTD)')->count(),
                'fatality' => $incidents->where('klasifikasi_kejadiannya', 'Fatality')->count(),
                'fire_incident' => $incidents->where('klasifikasi_kejadiannya', 'Fire Incident')->count(),
                'road_incident' => $incidents->where('klasifikasi_kejadiannya', 'Road Incident')->count(),
            ];
        }

        return view('adminsystem.dashboard.spi', compact('data'));
    }
























    public function operator_dashboard(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $query = DB::table('ppe_fix');

        if ($year) {
            $query->whereYear('tanggal_shift_kerja', $year);
        }

        if ($month) {
            $query->whereMonth('tanggal_shift_kerja', $month);
            $groupBy = DB::raw('DATE(tanggal_shift_kerja)');
            $selectDate = "DATE(tanggal_shift_kerja) as tanggal";
        } else {
            $groupBy = DB::raw('MONTH(tanggal_shift_kerja)');
            $selectDate = "MONTH(tanggal_shift_kerja) as bulan";
        }

        $data = $query->selectRaw("
        $selectDate,
        SUM(jumlah_patuh_apd_karyawan) as patuh_karyawan,
        (
            SUM(jumlah_tidak_patuh_helm_karyawan) +
            SUM(jumlah_tidak_patuh_sepatu_karyawan) +
            SUM(jumlah_tidak_patuh_pelindung_mata_karyawan) +
            SUM(jumlah_tidak_patuh_safety_harness_karyawan) +
            SUM(jumlah_tidak_patuh_apd_lainnya_karyawan)
        ) as tidak_patuh_karyawan,
        SUM(jumlah_patuh_apd_kontraktor) as patuh_kontraktor,
        (
            SUM(jumlah_tidak_patuh_helm_kontraktor) +
            SUM(jumlah_tidak_patuh_sepatu_kontraktor) +
            SUM(jumlah_tidak_patuh_pelindung_mata_kontraktor) +
            SUM(jumlah_tidak_patuh_safety_harness_kontraktor) +
            SUM(jumlah_tidak_patuh_apd_lainnya_kontraktor)
        ) as tidak_patuh_kontraktor,
        SUM(jumlah_tidak_patuh_helm_karyawan) as tidak_patuh_helm_karyawan,
        SUM(jumlah_tidak_patuh_sepatu_karyawan) as tidak_patuh_sepatu_karyawan,
        SUM(jumlah_tidak_patuh_helm_kontraktor) as tidak_patuh_helm_kontraktor,
        SUM(jumlah_tidak_patuh_sepatu_kontraktor) as tidak_patuh_sepatu_kontraktor
    ")
            ->groupBy($groupBy)
            ->orderBy($groupBy)
            ->get();

        $labels = [];
        $employeeData = [];
        $contractorData = [];
        $tidak_patuh_karyawan = [];
        $patuh_karyawan = [];
        $tidak_patuh_kontraktor = [];
        $patuh_kontraktor = [];
        $employeeHelmData = [];
        $employeeSepatuData = [];
        $contractorHelmData = [];
        $contractorSepatuData = [];

        foreach ($data as $item) {
            if ($month) {
                $labels[] = date('d M', strtotime($item->tanggal));
            } else {
                $labels[] = date('M', mktime(0, 0, 0, $item->bulan, 10));
            }

            $total_karyawan = $item->patuh_karyawan + $item->tidak_patuh_karyawan;
            $total_kontraktor = $item->patuh_kontraktor + $item->tidak_patuh_kontraktor;

            $employeeData[] = $total_karyawan > 0 ? round(($item->patuh_karyawan / $total_karyawan) * 100, 2) : 0;
            $contractorData[] = $total_kontraktor > 0 ? round(($item->patuh_kontraktor / $total_kontraktor) * 100, 2) : 0;

            $tidak_patuh_karyawan[] = $item->tidak_patuh_karyawan;
            $patuh_karyawan[] = $item->patuh_karyawan;
            $tidak_patuh_kontraktor[] = $item->tidak_patuh_kontraktor;
            $patuh_kontraktor[] = $item->patuh_kontraktor;

            $employeeHelmData[] = $item->tidak_patuh_helm_karyawan;
            $employeeSepatuData[] = $item->tidak_patuh_sepatu_karyawan;
            $contractorHelmData[] = $item->tidak_patuh_helm_kontraktor;
            $contractorSepatuData[] = $item->tidak_patuh_sepatu_kontraktor;
        }

        $years = DB::table('ppe_fix')->selectRaw('YEAR(tanggal_shift_kerja) as year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $months = DB::table('ppe_fix')->selectRaw('MONTH(tanggal_shift_kerja) as month')->distinct()->orderBy('month', 'desc')->pluck('month');

        $targetEmployee = Target::where('key', 'target_employee_compliance')->value('value');
        $targetContractor = Target::where('key', 'target_contractor_compliance')->value('value');

        return view('operator.dashboard.dashboard', compact(
            'labels',
            'employeeData',
            'contractorData',
            'years',
            'months',
            'year',
            'month',
            'targetEmployee',
            'targetContractor',
            'tidak_patuh_kontraktor',
            'patuh_kontraktor',
            'tidak_patuh_karyawan',
            'patuh_karyawan',
            'employeeHelmData',
            'employeeSepatuData',
            'contractorHelmData',
            'contractorSepatuData'
        ));
    }



    public function operator_budget()
    {
        $budget_fixs = BudgetFix::all();
        return view('operator.dashboard.budget', compact('budget_fixs'));
    }
    public function operator_ncr(Request $request)
    {
        $year = $request->input('year');

        $ncr_fixs = SentNcr::when($year, function ($query) use ($year) {
            return $query->whereYear('tanggal_shift_kerja', $year);
        })->get();

        $openCount = $ncr_fixs->where('status_ncr', 'Open')->count();
        $closedCount = $ncr_fixs->where('status_ncr', 'Closed')->count();
        $years = SentNcr::selectRaw('YEAR(tanggal_shift_kerja) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        return view('operator.dashboard.ncr', compact('ncr_fixs', 'openCount', 'closedCount', 'years', 'year'));
    }

    public function operator_setComplianceTarget(Request $request)
    {
        $request->validate([
            'type' => 'required|in:employee,contractor',
            'target' => 'required|numeric|min:0|max:100',
        ]);

        $type = $request->type;
        $target = $request->target;

        // Simpan ke database atau cache (tergantung implementasi kamu)
        Target::updateOrCreate(
            ['key' => "target_{$type}_compliance"],
            ['value' => $target]
        );

        return redirect()->back()->with('success', 'Target compliance berhasil diperbarui.');
    }


    public function operator_incident(Request $request)
    {
        // Default hari ini jika tidak diisi
        $shiftDate = $request->input('shift_date')
            ? Carbon::parse($request->input('shift_date'))->format('Y-m-d')
            : now()->format('Y-m-d');
        $shift = $request->input('shift');

        if ($request->has(['shift_date', 'shift'])) {
            $request->validate([
                'shift_date' => 'required|date',
                'shift' => 'required|string',
            ]);
        }

        if (!$shift) {
            return view('operator.dashboard.dashboard-incident', [
                'daysSinceLastLTA' => 0,
                'daysSinceLastWLTA' => 0,
                'manHoursSinceLastLTA' => 0,
                'manHoursSinceLastWLTA' => 0,
                'totalLTA' => 0,
                'totalWLTA' => 0,
                'totalManHours' => 0,
                'lastLTAIncidentDate' => null,
                'selectedDate' => $shiftDate,
                'targetManHours' => 0,
            ]);
        }

        $data = SentIncident::whereDate('shift_date', $shiftDate)
            ->where('shift', $shift)
            ->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk tanggal dan shift tersebut.');
        }
        $targetManHours = TargetManHours::value('target_manhours');

        $lastLtaDate = SentIncident::where('lta', 1)
            ->whereDate('shift_date', '<=', $shiftDate)
            ->orderByDesc('shift_date')
            ->value('shift_date');

        return view('operator.dashboard.dashboard-incident', [
            'daysSinceLastLTA' => $data->total_safe_day_lta2 ?? 0,
            'daysSinceLastWLTA' => $data->total_safe_day_wlta ?? 0,
            'manHoursSinceLastLTA' => $data->total_man_hours_lta ?? 0,
            'manHoursSinceLastWLTA' => $data->total_man_hours_wlta2 ?? 0,
            'totalLTA' => $data->total_lta_by_year ?? 0,
            'totalWLTA' => $data->total_wlta_by_year ?? 0,
            'totalManHours' => $data->total_man_hours ?? 0,
            'lastLTAIncidentDate' => $lastLtaDate,
            'selectedDate' => $shiftDate,
            'targetManHours' => $targetManHours,
        ]);
    }


    public function operator_updateTargetManHours(Request $request)
    {
        $request->validate([
            'target_manhours' => 'required|integer|min:0',
        ]);

        // Misalnya hanya satu row (single config row)
        $setting = TargetManHours::first();
        if (!$setting) {
            $setting = new TargetManHours();
        }

        $setting->target_manhours = $request->target_manhours;
        $setting->save();

        return redirect()->back()->with('success', 'Target updated successfully.');
    }

    public function operator_spi(Request $request)
    {
        $data = [];

        // Loop per bulan dari Jan (1) sampai Dec (12)
        $year = $request->input('year', date('Y')); // default tahun ini

        $spiData = [];

        for ($month = 1; $month <= 12; $month++) {
            $incidents = DB::table('incidents_fix')
                ->whereMonth('shift_date', $month)
                ->whereYear('shift_date', $year)
                ->get();

            $data[] = [
                'month' => Carbon::create()->month($month)->format('M'),
                'lta' => $incidents->where('lost_workdays_case', true)->count(),
                'wlta' => $incidents->whereIn('klasifikasi_kejadiannya', [
                    'First Aid',
                    'Medical Treatment Case (MTC)',
                    'Restricted Work Case (RWC)'
                ])->count(),

                'ltifr' => $incidents->sum('jumlah_hari_hilang'),
                'man_hours' => $incidents->sum('total_man_hours'),
                'near_miss' => $incidents->where('klasifikasi_kejadiannya', 'Near Miss')->count(),
                'illness_sick' => $incidents->where('klasifikasi_kejadiannya', 'Illness/Sick')->count(),
                'first_aid_case' => $incidents->where('klasifikasi_kejadiannya', 'First Aid')->count(),
                'medical_treatment_case' => $incidents->where('klasifikasi_kejadiannya', 'Medical Treatment Case (MTC)')->count(),
                'restricted_work_case' => $incidents->where('klasifikasi_kejadiannya', 'Restricted Work Case (RWC)')->count(),
                'lost_workdays_case' => $incidents->where('klasifikasi_kejadiannya', 'Lost Workdays Case (LWC)')->count(),
                'permanent_partial_disability' => $incidents->where('klasifikasi_kejadiannya', 'Permanent Partial Disability (PPD)')->count(),
                'permanent_total_disability' => $incidents->where('klasifikasi_kejadiannya', 'Permanent Total Disability (PTD)')->count(),
                'fatality' => $incidents->where('klasifikasi_kejadiannya', 'Fatality')->count(),
                'fire_incident' => $incidents->where('klasifikasi_kejadiannya', 'Fire Incident')->count(),
                'road_incident' => $incidents->where('klasifikasi_kejadiannya', 'Road Incident')->count(),
            ];
        }

        return view('operator.dashboard.spi', compact('data'));
    }
















    public function tamu_dashboard(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $query = DB::table('ppe_fix');

        if ($year) {
            $query->whereYear('tanggal_shift_kerja', $year);
        }

        if ($month) {
            $query->whereMonth('tanggal_shift_kerja', $month);
            $groupBy = DB::raw('DATE(tanggal_shift_kerja)');
            $selectDate = "DATE(tanggal_shift_kerja) as tanggal";
        } else {
            $groupBy = DB::raw('MONTH(tanggal_shift_kerja)');
            $selectDate = "MONTH(tanggal_shift_kerja) as bulan";
        }

        $data = $query->selectRaw("
        $selectDate,
        SUM(jumlah_patuh_apd_karyawan) as patuh_karyawan,
        (
            SUM(jumlah_tidak_patuh_helm_karyawan) +
            SUM(jumlah_tidak_patuh_sepatu_karyawan) +
            SUM(jumlah_tidak_patuh_pelindung_mata_karyawan) +
            SUM(jumlah_tidak_patuh_safety_harness_karyawan) +
            SUM(jumlah_tidak_patuh_apd_lainnya_karyawan)
        ) as tidak_patuh_karyawan,
        SUM(jumlah_patuh_apd_kontraktor) as patuh_kontraktor,
        (
            SUM(jumlah_tidak_patuh_helm_kontraktor) +
            SUM(jumlah_tidak_patuh_sepatu_kontraktor) +
            SUM(jumlah_tidak_patuh_pelindung_mata_kontraktor) +
            SUM(jumlah_tidak_patuh_safety_harness_kontraktor) +
            SUM(jumlah_tidak_patuh_apd_lainnya_kontraktor)
        ) as tidak_patuh_kontraktor,
        SUM(jumlah_tidak_patuh_helm_karyawan) as tidak_patuh_helm_karyawan,
        SUM(jumlah_tidak_patuh_sepatu_karyawan) as tidak_patuh_sepatu_karyawan,
        SUM(jumlah_tidak_patuh_helm_kontraktor) as tidak_patuh_helm_kontraktor,
        SUM(jumlah_tidak_patuh_sepatu_kontraktor) as tidak_patuh_sepatu_kontraktor
    ")
            ->groupBy($groupBy)
            ->orderBy($groupBy)
            ->get();

        $labels = [];
        $employeeData = [];
        $contractorData = [];
        $tidak_patuh_karyawan = [];
        $patuh_karyawan = [];
        $tidak_patuh_kontraktor = [];
        $patuh_kontraktor = [];
        $employeeHelmData = [];
        $employeeSepatuData = [];
        $contractorHelmData = [];
        $contractorSepatuData = [];

        foreach ($data as $item) {
            if ($month) {
                $labels[] = date('d M', strtotime($item->tanggal));
            } else {
                $labels[] = date('M', mktime(0, 0, 0, $item->bulan, 10));
            }

            $total_karyawan = $item->patuh_karyawan + $item->tidak_patuh_karyawan;
            $total_kontraktor = $item->patuh_kontraktor + $item->tidak_patuh_kontraktor;

            $employeeData[] = $total_karyawan > 0 ? round(($item->patuh_karyawan / $total_karyawan) * 100, 2) : 0;
            $contractorData[] = $total_kontraktor > 0 ? round(($item->patuh_kontraktor / $total_kontraktor) * 100, 2) : 0;

            $tidak_patuh_karyawan[] = $item->tidak_patuh_karyawan;
            $patuh_karyawan[] = $item->patuh_karyawan;
            $tidak_patuh_kontraktor[] = $item->tidak_patuh_kontraktor;
            $patuh_kontraktor[] = $item->patuh_kontraktor;

            $employeeHelmData[] = $item->tidak_patuh_helm_karyawan;
            $employeeSepatuData[] = $item->tidak_patuh_sepatu_karyawan;
            $contractorHelmData[] = $item->tidak_patuh_helm_kontraktor;
            $contractorSepatuData[] = $item->tidak_patuh_sepatu_kontraktor;
        }

        $years = DB::table('ppe_fix')->selectRaw('YEAR(tanggal_shift_kerja) as year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $months = DB::table('ppe_fix')->selectRaw('MONTH(tanggal_shift_kerja) as month')->distinct()->orderBy('month', 'desc')->pluck('month');

        $targetEmployee = Target::where('key', 'target_employee_compliance')->value('value');
        $targetContractor = Target::where('key', 'target_contractor_compliance')->value('value');

        return view('tamu.dashboard.dashboard', compact(
            'labels',
            'employeeData',
            'contractorData',
            'years',
            'months',
            'year',
            'month',
            'targetEmployee',
            'targetContractor',
            'tidak_patuh_kontraktor',
            'patuh_kontraktor',
            'tidak_patuh_karyawan',
            'patuh_karyawan',
            'employeeHelmData',
            'employeeSepatuData',
            'contractorHelmData',
            'contractorSepatuData'
        ));
    }
    public function tamu_budget()
    {
        $budget_fixs = BudgetFix::all();
        return view('tamu.dashboard.budget', compact('budget_fixs'));
    }
    public function tamu_ncr(Request $request)
    {
        $year = $request->input('year');

        $ncr_fixs = SentNcr::when($year, function ($query) use ($year) {
            return $query->whereYear('tanggal_shift_kerja', $year);
        })->get();

        $openCount = $ncr_fixs->where('status_ncr', 'Open')->count();
        $closedCount = $ncr_fixs->where('status_ncr', 'Closed')->count();
        $years = SentNcr::selectRaw('YEAR(tanggal_shift_kerja) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        return view('tamu.dashboard.ncr', compact('ncr_fixs', 'openCount', 'closedCount', 'years', 'year'));
    }

    public function tamu_setComplianceTarget(Request $request)
    {
        $request->validate([
            'type' => 'required|in:employee,contractor',
            'target' => 'required|numeric|min:0|max:100',
        ]);

        $type = $request->type;
        $target = $request->target;

        // Simpan ke database atau cache (tergantung implementasi kamu)
        Target::updateOrCreate(
            ['key' => "target_{$type}_compliance"],
            ['value' => $target]
        );

        return redirect()->back()->with('success', 'Target compliance berhasil diperbarui.');
    }


    public function tamu_incident(Request $request)
    {
        // Default hari ini jika tidak diisi
        $shiftDate = $request->input('shift_date')
            ? Carbon::parse($request->input('shift_date'))->format('Y-m-d')
            : now()->format('Y-m-d');
        $shift = $request->input('shift');

        if ($request->has(['shift_date', 'shift'])) {
            $request->validate([
                'shift_date' => 'required|date',
                'shift' => 'required|string',
            ]);
        }

        if (!$shift) {
            return view('tamu.dashboard.dashboard-incident', [
                'daysSinceLastLTA' => 0,
                'daysSinceLastWLTA' => 0,
                'manHoursSinceLastLTA' => 0,
                'manHoursSinceLastWLTA' => 0,
                'totalLTA' => 0,
                'totalWLTA' => 0,
                'totalManHours' => 0,
                'lastLTAIncidentDate' => null,
                'selectedDate' => $shiftDate,
                'targetManHours' => 0,
            ]);
        }

        $data = SentIncident::whereDate('shift_date', $shiftDate)
            ->where('shift', $shift)
            ->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk tanggal dan shift tersebut.');
        }
        $targetManHours = TargetManHours::value('target_manhours');

        $lastLtaDate = SentIncident::where('lta', 1)
            ->whereDate('shift_date', '<=', $shiftDate)
            ->orderByDesc('shift_date')
            ->value('shift_date');

        return view('tamu.dashboard.dashboard-incident', [
            'daysSinceLastLTA' => $data->total_safe_day_lta2 ?? 0,
            'daysSinceLastWLTA' => $data->total_safe_day_wlta ?? 0,
            'manHoursSinceLastLTA' => $data->total_man_hours_lta ?? 0,
            'manHoursSinceLastWLTA' => $data->total_man_hours_wlta2 ?? 0,
            'totalLTA' => $data->total_lta_by_year ?? 0,
            'totalWLTA' => $data->total_wlta_by_year ?? 0,
            'totalManHours' => $data->total_man_hours ?? 0,
            'lastLTAIncidentDate' => $lastLtaDate,
            'selectedDate' => $shiftDate,
            'targetManHours' => $targetManHours,
        ]);
    }


    public function tamu_updateTargetManHours(Request $request)
    {
        $request->validate([
            'target_manhours' => 'required|integer|min:0',
        ]);

        // Misalnya hanya satu row (single config row)
        $setting = TargetManHours::first();
        if (!$setting) {
            $setting = new TargetManHours();
        }

        $setting->target_manhours = $request->target_manhours;
        $setting->save();

        return redirect()->back()->with('success', 'Target updated successfully.');
    }

    public function tamu_spi(Request $request)
    {
        $data = [];

        // Loop per bulan dari Jan (1) sampai Dec (12)
        $year = $request->input('year', date('Y')); // default tahun ini

        $spiData = [];

        for ($month = 1; $month <= 12; $month++) {
            $incidents = DB::table('incidents_fix')
                ->whereMonth('shift_date', $month)
                ->whereYear('shift_date', $year)
                ->get();

            $data[] = [
                'month' => Carbon::create()->month($month)->format('M'),
                'lta' => $incidents->where('lost_workdays_case', true)->count(),
                'wlta' => $incidents->whereIn('klasifikasi_kejadiannya', [
                    'First Aid',
                    'Medical Treatment Case (MTC)',
                    'Restricted Work Case (RWC)'
                ])->count(),

                'ltifr' => $incidents->sum('jumlah_hari_hilang'),
                'man_hours' => $incidents->sum('total_man_hours'),
                'near_miss' => $incidents->where('klasifikasi_kejadiannya', 'Near Miss')->count(),
                'illness_sick' => $incidents->where('klasifikasi_kejadiannya', 'Illness/Sick')->count(),
                'first_aid_case' => $incidents->where('klasifikasi_kejadiannya', 'First Aid')->count(),
                'medical_treatment_case' => $incidents->where('klasifikasi_kejadiannya', 'Medical Treatment Case (MTC)')->count(),
                'restricted_work_case' => $incidents->where('klasifikasi_kejadiannya', 'Restricted Work Case (RWC)')->count(),
                'lost_workdays_case' => $incidents->where('klasifikasi_kejadiannya', 'Lost Workdays Case (LWC)')->count(),
                'permanent_partial_disability' => $incidents->where('klasifikasi_kejadiannya', 'Permanent Partial Disability (PPD)')->count(),
                'permanent_total_disability' => $incidents->where('klasifikasi_kejadiannya', 'Permanent Total Disability (PTD)')->count(),
                'fatality' => $incidents->where('klasifikasi_kejadiannya', 'Fatality')->count(),
                'fire_incident' => $incidents->where('klasifikasi_kejadiannya', 'Fire Incident')->count(),
                'road_incident' => $incidents->where('klasifikasi_kejadiannya', 'Road Incident')->count(),
            ];
        }

        return view('tamu.dashboard.spi', compact('data'));
    }

}
