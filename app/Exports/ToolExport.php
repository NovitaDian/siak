<?php

namespace App\Exports;

use App\Models\SentToolReport;
use App\Models\ToolInspectionFix;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ToolExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $start, $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        $query = SentToolReport::select([
            'id',
            'writer',
            'draft_id',
            'alat_id',
            'nama_alat',
            'hse_inspector_id',
            'hse_inspector',
            'tanggal_pemeriksaan',
            'status_pemeriksaan',
            'status',
            'created_at',
        ]);

        if ($this->start && $this->end) {
            $query->whereBetween('tanggal_pemeriksaan', [$this->start, $this->end]);
        }

        return $query->orderBy('tanggal_pemeriksaan', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Penulis',
            'ID Draft',
            'ID Alat',
            'Nama Alat',
            'ID HSE Inspector',
            'Nama HSE Inspector',
            'Tanggal Pemeriksaan',
            'Status Pemeriksaan',
            'Status',
            'Tanggal Dibuat',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id ?? '-',
            $row->writer ?? '-',
            $row->draft_id ?? '-',
            $row->alat_id ?? '-',
            $row->nama_alat ?? '-',
            $row->hse_inspector_id ?? '-',
            $row->hse_inspector ?? '-',
            $row->tanggal_pemeriksaan ?? '-',
            $row->status_pemeriksaan ?? '-',
            $row->status ?? '-',
            optional($row->created_at)->format('Y-m-d H:i') ?? '-',
        ];
    }
}
