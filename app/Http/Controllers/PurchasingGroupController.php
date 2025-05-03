<?php

namespace App\Http\Controllers;

use App\Models\PurchasingGroup;
use Illuminate\Http\Request;

class PurchasingGroupController extends Controller
{
    public function index()
    {
        $purs = PurchasingGroup::all();
        return view('adminsystem.master.purchasinggroup.index', compact('purs'));
    }

    public function create()
    {
        return view('adminsystem.master.purchasinggroup.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pur_grp' => 'required|string',
            'department' => 'required|string'
        ]);

        PurchasingGroup::create($request->all());

        return redirect()->route('adminsystem.purchasinggroup.index')->with('success', 'Purchase Request berhasil dibuat.');
    }

    public function edit($id)
    {
        $pur = PurchasingGroup::findOrFail($id);
        return view('adminsystem.master.purchasinggroup.edit', compact('pur'));
    }

    public function update(Request $request, $id)
    {
        $pur = PurchasingGroup::findOrFail($id);

        $request->validate([
            'pur_grp' => 'required|string',
            'department' => 'required|string',
        ]);

        $pur->update($request->all());

        return redirect()->route('adminsystem.purchasinggroup.index')->with('success', 'Purchase Request berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pur = PurchasingGroup::findOrFail($id);
        $pur->delete();

        return redirect()->route('adminsystem.purchasinggroup.index')->with('success', 'Purchase Request berhasil dihapus.');
    }

}
