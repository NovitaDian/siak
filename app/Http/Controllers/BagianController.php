<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\Perusahaan;
use Illuminate\Http\Request;

class BagianController extends Controller
{
    public function index()
    {
        $bagians = Bagian::all();

        // Mengirim data ke view
        return view('adminsystem.master.bagian.index', compact('bagians'));
    }
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'perusahaan_code' => 'required|exists:perusahaan,perusahaan_code',
            'nama_bagian' => 'required|string|max:255',
        ]);

        // Get perusahaan_name based on selected perusahaan_code
        $perusahaan = Perusahaan::where('perusahaan_code', $request->perusahaan_code)->first();

        if ($perusahaan) {
            // Create a new Bagian record with the selected perusahaan_name
            Bagian::create([
                'perusahaan_code' => $request->perusahaan_code,
                'perusahaan_name' => $perusahaan->perusahaan_name,  // Auto-fill the perusahaan_name
                'nama_bagian' => $request->nama_bagian,
            ]);

            // Redirect with a success message
            return redirect()->route('adminsystem.bagian.index')->with('success', 'Bagian created successfully.');
        }

        // In case of failure, redirect with error message
        return redirect()->back()->with('error', 'Perusahaan not found.');
    }
    public function edit($id)
    {
        $bagian = Bagian::findOrFail($id);
        $perusahaans = Perusahaan::all();

        // Mengirim data ke view
        return view('adminsystem.master.bagian.edit', compact('perusahaans','bagian'));
    }
    public function create()
    {
        $perusahaans = Perusahaan::all();

        // Mengirim data ke view
        return view('adminsystem.master.bagian.create', compact('perusahaans'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'perusahaan_code' => 'required|exists:perusahaan,perusahaan_code',
            'nama_bagian' => 'required|string|max:255',
        ]);

        // Find the existing Bagian record
        $bagian = Bagian::findOrFail($id);

        // Retrieve the Perusahaan name based on the selected perusahaan_code
        $perusahaan = Perusahaan::where('perusahaan_code', $request->perusahaan_code)->first();

        // Update the Bagian record with new values
        $bagian->perusahaan_code = $request->perusahaan_code;
        $bagian->perusahaan_name = $perusahaan->perusahaan_name; // Set the perusahaan_name from the selected perusahaan_code
        $bagian->nama_bagian = $request->nama_bagian;
        $bagian->save(); // Save the changes

        // Redirect back with a success message
        return redirect()->route('adminsystem.bagian.index')->with('success', 'Data Bagian updated successfully!');
    }
    public function destroy($id)
    {
        $bagian = Bagian::find($id);
        if (!$bagian) {
            return response()->json(['message' => 'Bagian tidak ditemukan'], 404);
        }

        $bagian->delete();
        return redirect()->route('adminsystem.bagian.index')->with('notification', 'Bagian berhasil dikirim!');
    }





    
    public function operator_index()
    {
        $bagians = Bagian::all();

        // Mengirim data ke view
        return view('operator.master.bagian.index', compact('bagians'));
    }
    public function operator_store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'perusahaan_code' => 'required|exists:perusahaan,perusahaan_code',
            'nama_bagian' => 'required|string|max:255',
        ]);

        // Get perusahaan_name based on selected perusahaan_code
        $perusahaan = Perusahaan::where('perusahaan_code', $request->perusahaan_code)->first();

        if ($perusahaan) {
            // Create a new Bagian record with the selected perusahaan_name
            Bagian::create([
                'perusahaan_code' => $request->perusahaan_code,
                'perusahaan_name' => $perusahaan->perusahaan_name,  // Auto-fill the perusahaan_name
                'nama_bagian' => $request->nama_bagian,
            ]);

            // Redirect with a success message
            return redirect()->route('operator.bagian.index')->with('success', 'Bagian created successfully.');
        }

        // In case of failure, redirect with error message
        return redirect()->back()->with('error', 'Perusahaan not found.');
    }
    public function operator_edit($id)
    {
        $bagian = Bagian::findOrFail($id);
        $perusahaans = Perusahaan::all();

        // Mengirim data ke view
        return view('operator.master.bagian.edit', compact('perusahaans','bagian'));
    }
    public function operator_create()
    {
        $perusahaans = Perusahaan::all();

        // Mengirim data ke view
        return view('operator.master.bagian.create', compact('perusahaans'));
    }

    public function operator_update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'perusahaan_code' => 'required|exists:perusahaan,perusahaan_code',
            'nama_bagian' => 'required|string|max:255',
        ]);

        // Find the existing Bagian record
        $bagian = Bagian::findOrFail($id);

        // Retrieve the Perusahaan name based on the selected perusahaan_code
        $perusahaan = Perusahaan::where('perusahaan_code', $request->perusahaan_code)->first();

        // Update the Bagian record with new values
        $bagian->perusahaan_code = $request->perusahaan_code;
        $bagian->perusahaan_name = $perusahaan->perusahaan_name; // Set the perusahaan_name from the selected perusahaan_code
        $bagian->nama_bagian = $request->nama_bagian;
        $bagian->save(); // Save the changes

        // Redirect back with a success message
        return redirect()->route('operator.bagian.index')->with('success', 'Data Bagian updated successfully!');
    }
    public function operator_destroy($id)
    {
        $bagian = Bagian::find($id);
        if (!$bagian) {
            return response()->json(['message' => 'Bagian tidak ditemukan'], 404);
        }

        $bagian->delete();
        return redirect()->route('operator.bagian.index')->with('notification', 'Bagian berhasil dikirim!');
    }



}
