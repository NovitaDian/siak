<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Export NCR</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h3>Data Laporan NCR</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Draft ID</th>
                <th>Penulis</th>
                <th>Tanggal Shift</th>
                <th>Shift Kerja</th>
                <th>HS Officer 1</th>
                <th>HS Officer 2</th>
                <th>Tanggal Audit</th>
                <th>Auditee</th>
                <th>Perusahaan</th>
                <th>Bagian</th>
                <th>Referensi NCR</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Status NCR</th>
                <th>Durasi</th>
                <th>Estimasi</th>
                <th>Tindak Lanjut</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ncr_fixs as $ncr)
                <tr>
                    <td>{{ $ncr->id }}</td>
                    <td>{{ $ncr->draft_id }}</td>
                    <td>{{ $ncr->writer }}</td>
                    <td>{{ $ncr->tanggal_shift_kerja }}</td>
                    <td>{{ $ncr->shift_kerja }}</td>
                    <td>{{ $ncr->nama_hs_officer_1 }}</td>
                    <td>{{ $ncr->nama_hs_officer_2 }}</td>
                    <td>{{ $ncr->tanggal_audit }}</td>
                    <td>{{ $ncr->nama_auditee }}</td>
                    <td>{{ $ncr->perusahaan }}</td>
                    <td>{{ $ncr->nama_bagian }}</td>
                    <td>{{ $ncr->element_referensi_ncr }}</td>
                    <td>{{ $ncr->kategori_ketidaksesuaian }}</td>
                    <td>{{ $ncr->deskripsi_ketidaksesuaian }}</td>
                    <td>{{ $ncr->status }}</td>
                    <td>{{ $ncr->status_note }}</td>
                    <td>{{ $ncr->status_ncr }}</td>
                    <td>{{ $ncr->durasi_ncr }}</td>
                    <td>{{ $ncr->estimasi }}</td>
                    <td>{{ $ncr->tindak_lanjut }}</td>
                    <td>{{ $ncr->created_at ? $ncr->created_at->format('Y-m-d H:i') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
