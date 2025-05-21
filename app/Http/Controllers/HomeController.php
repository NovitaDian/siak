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
        } elseif ($role === 'guest') {
            return $this->guest($notes);
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

    private function guest($notes)
    {
        return view('guest.home', compact('notes'));
    }
    //  NOTES
    public function store(Request $request)
    {
        $request->validate([
            'note' => 'required',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048', // Adjust file types and size as needed
        ]);

        $note = Note::create([
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


    public function incident()
    {
        // Total man hours tahun berjalan (dihitung dari semua incident di tahun ini)
        $currentYear = now()->year;

        $totalManHours = SentIncident::whereYear('shift_date', $currentYear)
            ->sum('man_hours_per_day');

        // Hitung total LTA & WLTA tahun berjalan
        $totalLTA = SentIncident::whereIn('klasifikasi_kejadiannya', [
            'Lost Workdays Case (LWC)',
            'Permanent Partial Dissability (PPD)',
            'Permanent Total Dissability (PTD)'
        ])
            ->whereYear('shift_date', $currentYear)
            ->count();

        $totalWLTA = SentIncident::whereIn('klasifikasi_kejadiannya', [
            'First Aid Case (FAC)',
            'Medical Treatment Case (MTC)',
            'Restricted Work Case (RWC)'
        ])
            ->whereYear('shift_date', $currentYear)
            ->count();

        // Kejadian LTA terakhir
        $lastLTA = SentIncident::whereIn('klasifikasi_kejadiannya', [
            'Lost Workdays Case (LWC)',
            'Permanent Partial Dissability (PPD)',
            'Permanent Total Dissability (PTD)'
        ])
            ->orderBy('shift_date', 'desc')
            ->first();

        // Kejadian WLTA terakhir
        $lastWLTA = SentIncident::whereIn('klasifikasi_kejadiannya', [
            'First Aid Case (FAC)',
            'Medical Treatment Case (MTC)',
            'Restricted Work Case (RWC)'
        ])
            ->orderBy('shift_date', 'desc')
            ->first();

        // Hari sejak LTA/WLTA terakhir
        $daysSinceLastLTA = $lastLTA ? Carbon::parse($lastLTA->shift_date)->diffInWeekdays(now()) : 0;
        $daysSinceLastWLTA = $lastWLTA ? Carbon::parse($lastWLTA->shift_date)->diffInWeekdays(now()) : 0;

        // Jam kerja sejak LTA/WLTA terakhir
        $hoursSinceLastLTA = $lastLTA ? SentIncident::where('shift_date', '>', $lastLTA->shift_date)
            ->sum('man_hours_per_day') : 0;

        $hoursSinceLastWLTA = $lastWLTA ? SentIncident::where('shift_date', '>', $lastWLTA->shift_date)
            ->sum('man_hours_per_day') : 0;

        // Total tenaga kerja terakhir berdasarkan input terakhir
        $lastShiftIIncident = SentIncident::where('shift', 'Shift 1')->latest('shift_date')->first();
        $lastShiftIIIncident = SentIncident::where('shift', 'Shift 2')->latest('shift_date')->first();
        $lastShiftIIIIncident = SentIncident::where('shift', 'Shift 3')->latest('shift_date')->first();

        // Hitung total_work_force per shift
        $shiftI = ($lastShiftIIncident->jml_employee ?? 0)
            + ($lastShiftIIncident->jml_outsources ?? 0)
            + ($lastShiftIIncident->jml_security ?? 0)
            + ($lastShiftIIncident->jml_loading_stacking ?? 0)
            + ($lastShiftIIncident->jml_contractor ?? 0);

        $shiftII = ($lastShiftIIIncident->jml_employee ?? 0)
            + ($lastShiftIIIncident->jml_outsources ?? 0)
            + ($lastShiftIIIncident->jml_security ?? 0)
            + ($lastShiftIIIncident->jml_loading_stacking ?? 0)
            + ($lastShiftIIIncident->jml_contractor ?? 0);

        $shiftIII = ($lastShiftIIIIncident->jml_employee ?? 0)
            + ($lastShiftIIIIncident->jml_outsources ?? 0)
            + ($lastShiftIIIIncident->jml_security ?? 0)
            + ($lastShiftIIIIncident->jml_loading_stacking ?? 0)
            + ($lastShiftIIIIncident->jml_contractor ?? 0);

        $totalWorkForce = $shiftI + $shiftII + $shiftIII;

        return view('adminsystem.dashboard.dashboard-incident', [
            'totalManHours' => $totalManHours,
            'totalLTA' => $totalLTA,
            'totalWLTA' => $totalWLTA,
            'daysSinceLastLTA' => $daysSinceLastLTA,
            'daysSinceLastWLTA' => $daysSinceLastWLTA,
            'manHoursSinceLastLTA' => $hoursSinceLastLTA,
            'manHoursSinceLastWLTA' => $hoursSinceLastWLTA,
            'lastLTAIncidentDate' => $lastLTA ? $lastLTA->shift_date : null,
            'shift1' => $shiftI,
            'shift2' => $shiftII,
            'shift3' => $shiftIII,
            'totalShift' => $totalWorkForce
        ]);
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
                'wlta' => $incidents->where(function ($item) {
                    return $item->first_aid_case || $item->medical_treatment_case || $item->restricted_work_case;
                })->count(),
                'ltifr' => $incidents->sum('jumlah_hari_hilang'),
                'man_hours' => $incidents->sum('total_man_hours'),
                'near_miss' => $incidents->where('near_miss', 1)->count(),
                'illness_sick' => $incidents->where('illness_sick', 1)->count(),
                'first_aid_case' => $incidents->where('first_aid_case', 1)->count(),
                'medical_treatment_case' => $incidents->where('medical_treatment_case', 1)->count(),
                'restricted_work_case' => $incidents->where('restricted_work_case', 1)->count(),
                'lost_workdays_case' => $incidents->where('lost_workdays_case', 1)->count(),
                'permanent_partial_dissability' => $incidents->where('permanent_partial_dissability', 1)->count(),
                'permanent_total_dissability' => $incidents->where('permanent_total_dissability', 1)->count(),
                'fatality' => $incidents->where('fatality', 1)->count(),
                'fire_incident' => $incidents->where('fire_incident', 1)->count(),
                'road_incident' => $incidents->where('road_incident', 1)->count(),
            ];
        }

        return view('operator.dashboard.spi', compact('data'));
    }

    public function operator_dashboard()
    {
        return view('operator.dashboard.dashboard');
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



    public function operator_incident()
    {
        // Total man hours tahun berjalan (dihitung dari semua incident di tahun ini)
        $currentYear = now()->year;

        $totalManHours = SentIncident::whereYear('shift_date', $currentYear)
            ->sum('man_hours_per_day');

        // Hitung total LTA & WLTA tahun berjalan
        $totalLTA = SentIncident::whereIn('klasifikasi_kejadiannya', [
            'Lost Workdays Case (LWC)',
            'Permanent Partial Dissability (PPD)',
            'Permanent Total Dissability (PTD)'
        ])
            ->whereYear('shift_date', $currentYear)
            ->count();

        $totalWLTA = SentIncident::whereIn('klasifikasi_kejadiannya', [
            'First Aid Case (FAC)',
            'Medical Treatment Case (MTC)',
            'Restricted Work Case (RWC)'
        ])
            ->whereYear('shift_date', $currentYear)
            ->count();

        // Kejadian LTA terakhir
        $lastLTA = SentIncident::whereIn('klasifikasi_kejadiannya', [
            'Lost Workdays Case (LWC)',
            'Permanent Partial Dissability (PPD)',
            'Permanent Total Dissability (PTD)'
        ])
            ->orderBy('shift_date', 'desc')
            ->first();

        // Kejadian WLTA terakhir
        $lastWLTA = SentIncident::whereIn('klasifikasi_kejadiannya', [
            'First Aid Case (FAC)',
            'Medical Treatment Case (MTC)',
            'Restricted Work Case (RWC)'
        ])
            ->orderBy('shift_date', 'desc')
            ->first();

        // Hari sejak LTA/WLTA terakhir
        $daysSinceLastLTA = $lastLTA ? Carbon::parse($lastLTA->shift_date)->diffInWeekdays(now()) : 0;
        $daysSinceLastWLTA = $lastWLTA ? Carbon::parse($lastWLTA->shift_date)->diffInWeekdays(now()) : 0;

        // Jam kerja sejak LTA/WLTA terakhir
        $hoursSinceLastLTA = $lastLTA ? SentIncident::where('shift_date', '>', $lastLTA->shift_date)
            ->sum('man_hours_per_day') : 0;

        $hoursSinceLastWLTA = $lastWLTA ? SentIncident::where('shift_date', '>', $lastWLTA->shift_date)
            ->sum('man_hours_per_day') : 0;

        // Total tenaga kerja terakhir berdasarkan input terakhir
        $lastShiftIIncident = SentIncident::where('shift', 'Shift 1')->latest('shift_date')->first();
        $lastShiftIIIncident = SentIncident::where('shift', 'Shift 2')->latest('shift_date')->first();
        $lastShiftIIIIncident = SentIncident::where('shift', 'Shift 3')->latest('shift_date')->first();

        // Hitung total_work_force per shift
        $shiftI = ($lastShiftIIncident->jml_employee ?? 0)
            + ($lastShiftIIncident->jml_outsources ?? 0)
            + ($lastShiftIIncident->jml_security ?? 0)
            + ($lastShiftIIncident->jml_loading_stacking ?? 0)
            + ($lastShiftIIncident->jml_contractor ?? 0);

        $shiftII = ($lastShiftIIIncident->jml_employee ?? 0)
            + ($lastShiftIIIncident->jml_outsources ?? 0)
            + ($lastShiftIIIncident->jml_security ?? 0)
            + ($lastShiftIIIncident->jml_loading_stacking ?? 0)
            + ($lastShiftIIIncident->jml_contractor ?? 0);

        $shiftIII = ($lastShiftIIIIncident->jml_employee ?? 0)
            + ($lastShiftIIIIncident->jml_outsources ?? 0)
            + ($lastShiftIIIIncident->jml_security ?? 0)
            + ($lastShiftIIIIncident->jml_loading_stacking ?? 0)
            + ($lastShiftIIIIncident->jml_contractor ?? 0);

        $totalWorkForce = $shiftI + $shiftII + $shiftIII;

        return view('operator.dashboard.dashboard-incident', [
            'totalManHours' => $totalManHours,
            'totalLTA' => $totalLTA,
            'totalWLTA' => $totalWLTA,
            'daysSinceLastLTA' => $daysSinceLastLTA,
            'daysSinceLastWLTA' => $daysSinceLastWLTA,
            'manHoursSinceLastLTA' => $hoursSinceLastLTA,
            'manHoursSinceLastWLTA' => $hoursSinceLastWLTA,
            'lastLTAIncidentDate' => $lastLTA ? $lastLTA->shift_date : null,
            'shift1' => $shiftI,
            'shift2' => $shiftII,
            'shift3' => $shiftIII,
            'totalShift' => $totalWorkForce
        ]);
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
                'wlta' => $incidents->where(function ($item) {
                    return $item->first_aid_case || $item->medical_treatment_case || $item->restricted_work_case;
                })->count(),
                'ltifr' => $incidents->sum('jumlah_hari_hilang'),
                'man_hours' => $incidents->sum('total_man_hours'),
                'near_miss' => $incidents->where('near_miss', 1)->count(),
                'illness_sick' => $incidents->where('illness_sick', 1)->count(),
                'first_aid_case' => $incidents->where('first_aid_case', 1)->count(),
                'medical_treatment_case' => $incidents->where('medical_treatment_case', 1)->count(),
                'restricted_work_case' => $incidents->where('restricted_work_case', 1)->count(),
                'lost_workdays_case' => $incidents->where('lost_workdays_case', 1)->count(),
                'permanent_partial_dissability' => $incidents->where('permanent_partial_dissability', 1)->count(),
                'permanent_total_dissability' => $incidents->where('permanent_total_dissability', 1)->count(),
                'fatality' => $incidents->where('fatality', 1)->count(),
                'fire_incident' => $incidents->where('fire_incident', 1)->count(),
                'road_incident' => $incidents->where('road_incident', 1)->count(),
            ];
        }

        return view('operator.dashboard.spi', compact('data'));
    }
}
