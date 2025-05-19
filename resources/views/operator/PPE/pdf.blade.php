<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export PDF Observasi</title>
    <style>
        body {
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 4px;
            text-align: center;
            word-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Observasi</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Penulis</th>
                <th>Tanggal Shift</th>
                <th>Shift</th>
                <th>ID Inspector</th>
                <th>Nama Inspector</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Zona</th>
                <th>Lokasi</th>
                <th>APD Karyawan</th>
                <th>T.P Helm K</th>
                <th>T.P Sepatu K</th>
                <th>T.P Mata K</th>
                <th>T.P Harness K</th>
                <th>T.P Lainnya K</th>
                <th>Keterangan</th>
                <th>APD Kontraktor</th>
                <th>T.P Helm C</th>
                <th>T.P Sepatu C</th>
                <th>T.P Mata C</th>
                <th>T.P Harness C</th>
                <th>T.P Lainnya C</th>
                <th>Durasi</th>
                <th>Catatan</th>
                <th>Status PPE</th>
                <th>Status</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ppe_fixs as $d)
                <tr>
                    <td>{{ $d->id }}</td>
                    <td>{{ $d->writer }}</td>
                    <td>{{ $d->tanggal_shift_kerja }}</td>
                    <td>{{ $d->shift_kerja }}</td>
                    <td>{{ $d->hse_inspector_id }}</td>
                    <td>{{ $d->nama_hse_inspector }}</td>
                    <td>{{ $d->jam_mulai }}</td>
                    <td>{{ $d->jam_selesai }}</td>
                    <td>{{ $d->zona_pengawasan }}</td>
                    <td>{{ $d->lokasi_observasi }}</td>
                    <td>{{ $d->jumlah_patuh_apd_karyawan }}</td>
                    <td>{{ $d->jumlah_tidak_patuh_helm_karyawan }}</td>
                    <td>{{ $d->jumlah_tidak_patuh_sepatu_karyawan }}</td>
                    <td>{{ $d->jumlah_tidak_patuh_pelindung_mata_karyawan }}</td>
                    <td>{{ $d->jumlah_tidak_patuh_safety_harness_karyawan }}</td>
                    <td>{{ $d->jumlah_tidak_patuh_apd_lainnya_karyawan }}</td>
                    <td>{{ $d->keterangan_tidak_patuh }}</td>
                    <td>{{ $d->jumlah_patuh_apd_kontraktor }}</td>
                    <td>{{ $d->jumlah_tidak_patuh_helm_kontraktor }}</td>
                    <td>{{ $d->jumlah_tidak_patuh_sepatu_kontraktor }}</td>
                    <td>{{ $d->jumlah_tidak_patuh_pelindung_mata_kontraktor }}</td>
                    <td>{{ $d->jumlah_tidak_patuh_safety_harness_kontraktor }}</td>
                    <td>{{ $d->jumlah_tidak_patuh_apd_lainnya_kontraktor }}</td>
                    <td>{{ $d->durasi_ppe }}</td>
                    <td>{{ $d->status_note }}</td>
                    <td>{{ $d->status_ppe }}</td>
                    <td>{{ $d->status }}</td>
                    <td>{{ $d->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
