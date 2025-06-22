<?php

namespace App\Http\Controllers;

use App\Models\MaterialGroup;
use Illuminate\Http\Request;

class MaterialGroupController extends Controller
{
    
    public function index()
    {
        $material_groups = MaterialGroup::all();
        return view('adminsystem.master.material_group.index', compact('material_groups'));
    }
    
    public function create()
    {
        return view('adminsystem.master.material_group.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_group' => 'required|string'
        ]);

        MaterialGroup::create($request->all());

        return redirect()->route('adminsystem.material_group.index')->with('success', 'Purchase Request berhasil dibuat.');
    }

    public function edit($id)
    {
        $material_group = MaterialGroup::findOrFail($id);
        return view('adminsystem.master.material_group.edit', compact('material_group'));
    }

    public function update(Request $request, $id)
    {
        $material_group = MaterialGroup::findOrFail($id);

        $request->validate([
            'material_group' => 'required|string',
        ]);

        $material_group->update($request->all());

        return redirect()->route('adminsystem.material_group.index')->with('success', 'Purchase Request berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $material_group = MaterialGroup::findOrFail($id);
        $material_group->delete();

        return redirect()->route('adminsystem.material_group.index')->with('success', 'Purchase Request berhasil dihapus.');
    }


    public function operator_index()
    {
        $material_groups = MaterialGroup::all();
        return view('operator.master.material_group.index', compact('material_groups'));
    }
        public function operator_create()
    {
        return view('operator.master.material_group.create');
    }

    public function operator_store(Request $request)
    {
        $request->validate([
            'material_group' => 'required|string'
        ]);

        MaterialGroup::create($request->all());

        return redirect()->route('operator.material_group.index')->with('success', 'Purchase Request berhasil dibuat.');
    }

    public function operator_edit($id)
    {
        $material_group = MaterialGroup::findOrFail($id);
        return view('operator.master.material_group.edit', compact('material_group'));
    }

    public function operator_update(Request $request, $id)
    {
        $material_group = MaterialGroup::findOrFail($id);

        $request->validate([
            'material_group' => 'required|string',
        ]);

        $material_group->update($request->all());

        return redirect()->route('operator.material_group.index')->with('success', 'Purchase Request berhasil diperbarui.');
    }

    public function operator_destroy($id)
    {
        $material_group = MaterialGroup::findOrFail($id);
        $material_group->delete();

        return redirect()->route('operator.material_group.index')->with('success', 'Purchase Request berhasil dihapus.');
    }

}
