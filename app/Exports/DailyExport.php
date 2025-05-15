<?php

namespace App\Exports;

use App\Models\SentDaily;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;

class DailyExport implements FromCollection
{
    protected $start, $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        $query = SentDaily::query();

        if ($this->start && $this->end) {
            $query->whereBetween('tanggal_shift_kerja', [$this->start, $this->end]);
        }

        return $query->get(['tanggal_shift_kerja', 'shift_kerja', 'hse_inspector_id', 'rincian_laporan']);
    }
}


