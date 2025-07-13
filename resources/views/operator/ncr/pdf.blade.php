<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Export NCR - Single Entry Per Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #fff;
        }

        .header-title {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        td {
            padding: 8px;
            vertical-align: top;
        }

        .foto {
            text-align: center;
        }

        .foto img {
            max-width: 100%;
            max-height: 250px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .desc td {
            border-bottom: 1px solid #eee;
        }

        .desc .label {
            font-weight: bold;
            width: 35%;
            white-space: nowrap;
            color: #333;
        }

        .desc .value {
            width: 65%;
            color: #000;
        }

        @media print {
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>

<body>
    <h3 class="header-title">Data Laporan NCR</h3>

    @foreach($ncr_fixs as $ncr)
    <div class="page-break">

        <table>
            <tr>
                <td style="width: 35%;">
                    <div class="foto">
                        <strong>Foto NCR</strong><br>
                        @if($ncr->foto)
                        <img src="{{ public_path('storage/' . $ncr->foto) }}" alt="Foto NCR">
                        @else
                        <div class="no-image">Tidak ada foto</div>
                        @endif

                        <br><strong>Foto Closed</strong><br>
                        @if($ncr->foto_closed)
                        <img src="{{ public_path('storage/' . $ncr->foto_closed) }}" alt="Foto Closed">
                        @else
                        <div class="no-image">Tidak ada foto</div>
                        @endif
                    </div>
                </td>

                <td style="width: 65%;">
                    <table class="desc">
                        <tr><td class="label">Penulis:</td><td class="value">{{ $ncr->writer }}</td></tr>
                        <tr><td class="label">Tanggal Shift:</td><td class="value">{{ $ncr->tanggal_shift_kerja }}</td></tr>
                        <tr><td class="label">Shift:</td><td class="value">{{ $ncr->shift_kerja }}</td></tr>
                        <tr><td class="label">HS Officer 1:</td><td class="value">{{ $ncr->nama_hs_officer_1 }}</td></tr>
                        <tr><td class="label">HS Officer 2:</td><td class="value">{{ $ncr->nama_hs_officer_2 ?? '-' }}</td></tr>
                        <tr><td class="label">Tanggal Audit:</td><td class="value">{{ $ncr->tanggal_audit }}</td></tr>
                        <tr><td class="label">Auditee:</td><td class="value">{{ $ncr->nama_auditee }}</td></tr>
                        <tr><td class="label">Perusahaan:</td><td class="value">{{ $ncr->pers->perusahaan_name }}</td></tr>
                        <tr><td class="label">Bagian:</td><td class="value">{{ $ncr->nama_bagian }}</td></tr>
                        <tr><td class="label">Referensi:</td><td class="value">{{ $ncr->element_referensi_ncr }}</td></tr>
                        <tr><td class="label">Kategori:</td><td class="value">{{ $ncr->kategori_ketidaksesuaian }}</td></tr>
                        <tr><td class="label">Status:</td><td class="value">{{ $ncr->status_ncr }}</td></tr>
                        <tr><td class="label">Estimasi:</td><td class="value">{{ $ncr->estimasi }}</td></tr>
                        <tr><td class="label">Durasi:</td><td class="value">{{ $ncr->durasi_ncr ?? '-' }}</td></tr>
                        <tr><td class="label">Tindak Lanjut:</td><td class="value">{{ $ncr->tindak_lanjut }}</td></tr>
                        <tr><td class="label">Status Note:</td><td class="value">{{ $ncr->status_note ?? '-' }}</td></tr>
                        <tr><td class="label">Dibuat:</td><td class="value">{{ $ncr->created_at ? $ncr->created_at->format('Y-m-d H:i') : '-' }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>
    @endforeach
</body>
</html>
