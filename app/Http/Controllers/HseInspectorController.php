<?php

namespace App\Http\Controllers;

use App\Models\HseInspector;
use Illuminate\Http\Request;

class HseInspectorController extends Controller
{
    public function index()
    {
        $hse_inspectors = HseInspector::all();
        return view('adminsystem.master.hse_inspector.index', compact('hse_inspectors'));
    }

    public function create()
    {
        return view('adminsystem.master.hse_inspector.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
        ]);

        HseInspector::create($request->all());

        return redirect()->route('adminsystem.hse_inspector.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $inspector = HseInspector::findOrFail($id);
        return view('adminsystem.master.hse_inspector.edit', compact('inspector'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
        ]);

        $inspector = HseInspector::findOrFail($id);
        $inspector->update($request->all());

        return redirect()->route('adminsystem.hse_inspector.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $inspector = HseInspector::findOrFail($id);
        $inspector->delete();

        return redirect()->route('adminsystem.hse_inspector.index')->with('success', 'Data berhasil dihapus.');
    }
}
