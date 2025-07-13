<?php

namespace App\Exports;

use App\Models\SentPpe;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PpeExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $start, $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        $query = SentPpe::with(['user', 'inspectors'])->select([
            'id',
            'tanggal_shift_kerja',
            'shift_kerja',
            'hse_inspector_id',
            'jam_mulai',
            'jam_selesai',
            'zona_pengawasan',
            'lokasi_observasi',
            'jumlah_patuh_apd_karyawan',
            'jumlah_tidak_patuh_helm_karyawan',
            'jumlah_tidak_patuh_sepatu_karyawan',
            'jumlah_tidak_patuh_pelindung_mata_karyawan',
            'jumlah_tidak_patuh_safety_harness_karyawan',
            'jumlah_tidak_patuh_apd_lainnya_karyawan',
            'keterangan_tidak_patuh',
            'jumlah_patuh_apd_kontraktor',
            'jumlah_tidak_patuh_helm_kontraktor',
            'jumlah_tidak_patuh_sepatu_kontraktor',
            'jumlah_tidak_patuh_pelindung_mata_kontraktor',
            'jumlah_tidak_patuh_safety_harness_kontraktor',
            'jumlah_tidak_patuh_apd_lainnya_kontraktor',
            'status_ppe',
            'status',
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
            'Penulis',
            'Tanggal Shift Kerja',
            'Shift Kerja',
            'ID HSE Inspector',
            'Nama HSE Inspector',
            'Jam Mulai',
            'Jam Selesai',
            'Zona Pengawasan',
            'Lokasi Observasi',
            'Patuh APD Karyawan',
            'Tidak Patuh Helm (Karyawan)',
            'Tidak Patuh Sepatu (Karyawan)',
            'Tidak Patuh Pelindung Mata (Karyawan)',
            'Tidak Patuh Safety Harness (Karyawan)',
            'Tidak Patuh APD Lainnya (Karyawan)',
            'Keterangan Tidak Patuh',
            'Patuh APD Kontraktor',
            'Tidak Patuh Helm (Kontraktor)',
            'Tidak Patuh Sepatu (Kontraktor)',
            'Tidak Patuh Pelindung Mata (Kontraktor)',
            'Tidak Patuh Safety Harness (Kontraktor)',
            'Tidak Patuh APD Lainnya (Kontraktor)',
            'Durasi PPE',
            'Status Catatan',
            'Status PPE',
            'Status',
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
            $row->hse_inspector_id ?? '-',
            $row->nama_hse_inspector ?? '-',
            $row->jam_mulai ?? '-',
            $row->jam_selesai ?? '-',
            $row->zona_pengawasan ?? '-',
            $row->lokasi_observasi ?? '-',
            $row->jumlah_patuh_apd_karyawan ?? 0,
            $row->jumlah_tidak_patuh_helm_karyawan ?? 0,
            $row->jumlah_tidak_patuh_sepatu_karyawan ?? 0,
            $row->jumlah_tidak_patuh_pelindung_mata_karyawan ?? 0,
            $row->jumlah_tidak_patuh_safety_harness_karyawan ?? 0,
            $row->jumlah_tidak_patuh_apd_lainnya_karyawan ?? 0,
            $row->keterangan_tidak_patuh ?? '-',
            $row->jumlah_patuh_apd_kontraktor ?? 0,
            $row->jumlah_tidak_patuh_helm_kontraktor ?? 0,
            $row->jumlah_tidak_patuh_sepatu_kontraktor ?? 0,
            $row->jumlah_tidak_patuh_pelindung_mata_kontraktor ?? 0,
            $row->jumlah_tidak_patuh_safety_harness_kontraktor ?? 0,
            $row->jumlah_tidak_patuh_apd_lainnya_kontraktor ?? 0,
            $row->durasi_ppe ?? '-',
            $row->status_note ?? '-',
            $row->status_ppe ?? '-',
            $row->status ?? '-',
            optional($row->created_at)->format('Y-m-d H:i') ?? '-',
        ];
    }
}
