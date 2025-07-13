<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Observasi PPE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            word-wrap: break-word;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .non-compliant-list {
            margin-top: 10px;
            border: 1px solid #aaa;
            padding: 5px;
            background-color: #fdfdfd;
        }

        .non-compliant-list h4 {
            margin: 5px 0 10px;
            font-size: 12px;
        }

        .non-compliant-list table {
            font-size: 10px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    @foreach($ppe_fixs as $d)
    <h2>Laporan Observasi PPE</h2>

    <table>
        <tr>
            <td><strong>ID</strong></td>
            <td>{{ $d->id }}</td>
            <td><strong>Penulis</strong></td>
            <td>{{ $d->writer }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Shift</strong></td>
            <td>{{ $d->tanggal_shift_kerja }}</td>
            <td><strong>Shift</strong></td>
            <td>{{ $d->shift_kerja }}</td>
        </tr>
        <tr>
            <td><strong>ID Inspector</strong></td>
            <td>{{ $d->hse_inspector_id }}</td>
            <td><strong>Nama Inspector</strong></td>
            <td>{{ $d->inspector->name }}</td>
        </tr>
        <tr>
            <td><strong>Jam Mulai</strong></td>
            <td>{{ $d->jam_mulai }}</td>
            <td><strong>Jam Selesai</strong></td>
            <td>{{ $d->jam_selesai }}</td>
        </tr>
        <tr>
            <td><strong>Zona Pengawasan</strong></td>
            <td>{{ $d->zona_pengawasan }}</td>
            <td><strong>Lokasi Observasi</strong></td>
            <td>{{ $d->lokasi_observasi }}</td>
        </tr>
        <tr>
            <td><strong>APD Patuh Karyawan</strong></td>
            <td>{{ $d->jumlah_patuh_apd_karyawan ?? 0 }}</td>
            <td><strong>APD Patuh Kontraktor</strong></td>
            <td>{{ $d->jumlah_patuh_apd_kontraktor ?? 0 }}</td>
        </tr>
        <tr>
            <td><strong>Sepatu Tidak Patuh Karyawan</strong></td>
            <td>{{ $d->jumlah_tidak_patuh_sepatu_karyawan ?? 0 }}</td>
            <td><strong>Mata Tidak Patuh Karyawan</strong></td>
            <td>{{ $d->jumlah_tidak_patuh_pelindung_mata_karyawan ?? 0 }}</td>
        </tr>
        <tr>
            <td><strong>Harness Tidak Patuh Karyawan</strong></td>
            <td>{{ $d->jumlah_tidak_patuh_safety_harness_karyawan ?? 0 }}</td>
            <td><strong>Lainnya Tidak Patuh Karyawan</strong></td>
            <td>{{ $d->jumlah_tidak_patuh_apd_lainnya_karyawan ?? 0 }}</td>
        </tr>
        <tr>
            <td><strong>Helm Tidak Patuh Karyawan</strong></td>
            <td>{{ $d->jumlah_tidak_patuh_helm_karyawan ?? 0 }}</td>
            <td><strong>Helm Tidak Patuh Kontraktor</strong></td>
            <td>{{ $d->jumlah_tidak_patuh_helm_kontraktor ?? 0 }}</td>
        </tr>
        <tr>
            <td><strong>Sepatu Tidak Patuh Kontraktor</strong></td>
            <td>{{ $d->jumlah_tidak_patuh_sepatu_kontraktor ?? 0 }}</td>
            <td><strong>Mata Tidak Patuh Kontraktor</strong></td>
            <td>{{ $d->jumlah_tidak_patuh_pelindung_mata_kontraktor ?? 0 }}</td>
        </tr>
        <tr>
            <td><strong>Harness Tidak Patuh Kontraktor</strong></td>
            <td>{{ $d->jumlah_tidak_patuh_safety_harness_kontraktor ?? 0 }}</td>
            <td><strong>Lainnya Tidak Patuh Kontraktor</strong></td>
            <td>{{ $d->jumlah_tidak_patuh_apd_lainnya_kontraktor ?? 0 }}</td>
        </tr>
        <tr>
            <td><strong>Status PPE</strong></td>
            <td>{{ $d->status_ppe }}</td>
            <td><strong>Status Umum</strong></td>
            <td>{{ $d->status }}</td>
        </tr>
        <tr>
            <td><strong>Keterangan Ketidaksesuaian</strong></td>
            <td colspan="3">{{ $d->keterangan_tidak_patuh }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Dibuat</strong></td>
            <td colspan="3">{{ $d->created_at }}</td>
        </tr>
    </table>

    <div class="non-compliant-list">
        <h4>Daftar Pelanggar untuk ID PPE {{ $d->id }}</h4>
        <table>
            <thead>
                <tr>
                    <th>Nama Pelanggar</th>
                    <th>Perusahaan</th>
                    <th>Nama Bagian</th>
                    <th>Tindakan</th>
                    <th>Deskripsi Ketidaksesuaian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($d->nonCompliants as $nc)
                <tr>
                    <td>{{ $nc->nama_pelanggar }}</td>
                    <td>{{ $nc->pers->perusahaan_name }}</td>
                    <td>{{ $nc->nama_bagian }}</td>
                    <td>{{ $nc->tindakan }}</td>
                    <td>{{ $nc->deskripsi_ketidaksesuaian }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;">Tidak ada data pelanggar</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(!$loop->last)
    <div class="page-break"></div>
    @endif

    @endforeach

</body>

</html>