<?php

namespace App\Http\Controllers;

use App\Models\HariHilang;

class HariHilangController extends Controller
{
    public function index()
    {
        $jumlahs = HariHilang::all();
        return view('adminsystem.master.hari.index', compact('jumlahs'));
    }
}