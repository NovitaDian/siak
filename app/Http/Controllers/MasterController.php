<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\Gl_Account;
use App\Models\HseInspector;
use App\Models\Incident;
use App\Models\MaterialGroup;
use App\Models\Perusahaan;
use App\Models\PurchasingGroup;
use App\Models\Unit;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function index()
    {
        $incidents = Incident::all();
        return view('adminsystem.master.index', compact('incidents'));
    }
    public function glaccount()
    {
        $gls = Gl_Account::all();
        return view('adminsystem.master.glaccount.index', compact('gls'));
    }
    public function hse_inspector()
    {
        $hse_inspectors = HseInspector::all();
        return view('adminsystem.master.hse_inspector.index', compact('hse_inspectors'));
    }
    public function material_group()
    {
        $material_groups = MaterialGroup::all();
        return view('adminsystem.master.material_group.index', compact('material_groups'));
    }
    public function unit()
    {
        $units = Unit::all();
        return view('adminsystem.master.unit.index', compact('units'));
    }
    
    public function purchasinggroup()
    {
        $purs = PurchasingGroup::all();
        return view('adminsystem.master.purchasinggroup.index', compact('purs'));
    }
    public function bagian()
    {
        $bagians = Bagian::all();
        return view('adminsystem.master.bagian.index', compact('bagians'));
    }



    public function operator_index()
    {
        $incidents = Incident::all();
        return view('operator.master.index', compact('incidents'));
    }
      public function operator_hse_inspector()
    {
        $hse_inspectors = HseInspector::all();
        return view('operator.master.hse_inspector.index', compact('hse_inspectors'));
    }
    public function operator_material_group()
    {
        $material_groups = MaterialGroup::all();
        return view('operator.master.material_group.index', compact('material_groups'));
    }
    public function operator_unit()
    {
        $units = Unit::all();
        return view('operator.master.unit.index', compact('units'));
    }
    
    public function operator_purchasinggroup()
    {
        $purs = PurchasingGroup::all();
        return view('operator.master.purchasinggroup.index', compact('purs'));
    }
    public function operator_bagian()
    {
        $bagians = Bagian::all();
        return view('operator.master.bagian.index', compact('bagians'));
    }
}
