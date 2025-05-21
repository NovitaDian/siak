<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('adminsystem.master.unit.index', compact('units'));
    }
    public function create()
    {
        return view('adminsystem.master.unit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit' => 'required|string',
            'description' => 'required|string'
        ]);

        Unit::create($request->all());

        return redirect()->route('adminsystem.unit.index')->with('success', 'Purchase Request berhasil dibuat.');
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        return view('adminsystem.master.unit.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'unit' => 'required|string',
            'description' => 'required|string',
        ]);

        $unit->update($request->all());

        return redirect()->route('adminsystem.unit.index')->with('success', 'Purchase Request berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->route('adminsystem.unit.index')->with('success', 'Purchase Request berhasil dihapus.');
    }
    public function operator_index()
    {
        $units = Unit::all();
        return view('operator.master.unit.index', compact('units'));
    }
}
