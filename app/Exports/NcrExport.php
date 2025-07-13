<?php

namespace App\Exports;

use App\Models\SentNcr;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NcrExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $start, $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        $query = SentNcr::with(['pers', 'user'])->select([
            'id',
            'tanggal_shift_kerja',
            'shift_kerja',
            'nama_hs_officer_1',
            'nama_hs_officer_2',
            'tanggal_audit',
            'nama_auditee',
            'perusahaan_id',
            'nama_bagian',
            'element_referensi_ncr',
            'kategori_ketidaksesuaian',
            'deskripsi_ketidaksesuaian',
            'status',
            'status_note',
            'status_ncr',
            'durasi_ncr',
            'estimasi',
            'tindak_lanjut',
            'created_at',
        ]);

        if ($this->start && $this->end) {
            $query->whereBetween('tanggal_shift_kerja', [$this->start, $this->end]);
        }

        return $query->orderBy('tanggal_shift_kerja', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Draft ID',
            'Penulis',
            'Tanggal Shift Kerja',
            'Shift Kerja',
            'Nama HS Officer 1',
            'Nama HS Officer 2',
            'Tanggal Audit',
            'Nama Auditee',
            'Nama Bagian',
            'Element Referensi NCR',
            'Kategori Ketidaksesuaian',
            'Deskripsi Ketidaksesuaian',
            'Status',
            'Catatan Status',
            'Status NCR',
            'Durasi NCR',
            'Estimasi Penyelesaian',
            'Tindak Lanjut',
            'Tanggal Dibuat',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id ?? '-',
            $row->user->name ?? '-',
            $row->tanggal_shift_kerja ?? '-',
            $row->shift_kerja ?? '-',
            $row->nama_hs_officer_1 ?? '-',
            $row->nama_hs_officer_2 ?? '-',
            $row->tanggal_audit ?? '-',
            $row->nama_auditee ?? '-',
            $row->pers->perusahaan_name ?? '-',
            $row->nama_bagian ?? '-',
            $row->element_referensi_ncr ?? '-',
            $row->kategori_ketidaksesuaian ?? '-',
            $row->deskripsi_ketidaksesuaian ?? '-',
            $row->status ?? '-',
            $row->status_note ?? '-',
            $row->status_ncr ?? '-',
            $row->durasi_ncr ?? '-',
            $row->estimasi ?? '-',
            $row->tindak_lanjut ?? '-',
            optional($row->created_at)->format('Y-m-d H:i') ?? '-',
        ];
    }
}
