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
                <th>Tanggal Pemeriksaan</th>
                <th>Nama Alat</th>
                <th>Nomor Alat</th>
                <th>ID Inspector</th>
                <th>Nama Inspector</th>
                <th>Status Pemeriksaan</th>
                <th>Status</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tool_fixs as $d)
                <tr>
                    <td>{{ $d->id }}</td>
                    <td>{{ $d->user->name }}</td>
                    <td>{{ $d->tanggal_pemeriksaan }}</td>
                    <td>{{ $d->alat->namaAlat->nama_alat }}</td>
                    <td>{{ $d->alat->nomor }}</td>
                    <td>{{ $d->hse_inspector_id }}</td>
                    <td>{{ $d->inspector->name }}</td>
                    <td>{{ $d->status_pemeriksaan }}</td>
                    <td>{{ $d->status }}</td>
                    <td>{{ $d->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
