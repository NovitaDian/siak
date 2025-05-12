<?php

namespace App\Exports;

use App\Models\SentIncident;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;

class IncidentExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
      public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function view(): View
    {
        $data = SentIncident::whereBetween('shift_date', [$this->start, $this->end])->get();

        return view('exports.incidents', ['incidents' => $data]);
    }
    public function collection()
    {
        return SentIncident::all();
    }
     protected $start, $end;

  
}
