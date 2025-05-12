<?php

namespace App\Exports;

use App\Models\SentDaily;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;

class DailyExport
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
        $data = SentDaily::whereBetween('tanggal_shift_kerja', [$this->start, $this->end])->get();

        return view('exports.dailys', ['dailys' => $data]);
    }
   
     protected $start, $end;

}
