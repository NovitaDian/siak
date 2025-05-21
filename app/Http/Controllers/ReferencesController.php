<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document; 
use Illuminate\Support\Facades\Storage;

class ReferencesController extends Controller
{
    public function index()
    {
        $documents = Document::all();
        return view('adminsystem.references.index', compact('documents'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,docx,xlsx,jpg,png|max:2048', 
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $fileName, 'public'); 

        Document::create([
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_size' => $file->getSize() / 1024, 
        ]);

        return redirect()->route('adminsystem.references.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('adminsystem.references.index')->with('success', 'Dokumen berhasil dihapus.');
    }
    public function operator_index()
    {
        $documents = Document::all();
        return view('operator.references.index', compact('documents'));
    }
}