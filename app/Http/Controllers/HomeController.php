<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Note;
use App\Models\SentIncident;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function dashboard()
    {
        return view('adminsystem.dashboard.dashboard');
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
}
