<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $incidents = Incident::all();
        return view('adminsystem.report_menu.index', compact('incidents'));
    }
    public function incident()
    {
        $incidents = Incident::all();
        return view('adminsystem.incident.report', compact('incidents'));
    }
 
}
