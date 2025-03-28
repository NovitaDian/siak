<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Note;
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
    return view('adminsystem.dashboard');
}

}
