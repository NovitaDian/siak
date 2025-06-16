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

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo img {
            max-width: 150px;
            height: auto;
        }

        h3.header-title {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 1.6rem;
        }

        .page-break-container {
            page-break-after: always;
            break-after: page;
            margin-bottom: 40px;
            padding: 15px 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            background: #fefefe;
        }

        .entry-row {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .image-container {
            flex: 0 0 40%;
            text-align: center;
        }

        .image-container img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .no-image {
            padding: 40px 0;
            color: #999;
            font-style: italic;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #f9f9f9;
        }

        .desc-container {
            flex: 1 1 60%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        td {
            padding: 8px 10px;
            vertical-align: top;
            border-bottom: 1px solid #eee;
        }

        td.label {
            font-weight: bold;
            width: 40%;
            white-space: nowrap;
            color: #333;
        }

        td.value {
            width: 60%;
            color: #000;
        }

        @media print {
            body {
                margin: 10mm;
                background: none !important;
            }

            .page-break-container {
                page-break-after: always;
                break-after: page;
            }
        }
    </style>
</head>

<body>
    <h3 class="header-title" style="text-align: center; margin-bottom: 20px;">Data Laporan NCR</h3>

    @foreach($ncr_fixs as $ncr)
    <div style="page-break-after: always;">

        <table style="width: 100%; border: none;">
            <tr>
                <!-- Foto di kiri -->
                <td style="width: 35%; vertical-align: top;">
                    @if($ncr->foto)
                    <img src="{{ public_path('storage/' . $ncr->foto) }}"
                        alt="Foto NCR"
                        style="max-width: 100%; max-height: 300px;">
                    @else
                    <p>Tidak ada foto</p>
                    @endif
                </td>

                <!-- Deskripsi di kanan -->
                <td style="width: 65%; vertical-align: top;">
                    <table style="width: 100%; font-size: 11px; border-collapse: collapse;">
                        <tr>
                            <td style="font-weight: bold;">Penulis:</td>
                            <td>{{ $ncr->writer }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Tanggal Shift:</td>
                            <td>{{ $ncr->tanggal_shift_kerja }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Shift:</td>
                            <td>{{ $ncr->shift_kerja }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">HS Officer 1:</td>
                            <td>{{ $ncr->nama_hs_officer_1 }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">HS Officer 2:</td>
                            <td>{{ $ncr->nama_hs_officer_2 }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Tanggal Audit:</td>
                            <td>{{ $ncr->tanggal_audit }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Auditee:</td>
                            <td>{{ $ncr->nama_auditee }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Perusahaan:</td>
                            <td>{{ $ncr->perusahaan }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Bagian:</td>
                            <td>{{ $ncr->nama_bagian }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Referensi:</td>
                            <td>{{ $ncr->element_referensi_ncr }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Kategori:</td>
                            <td>{{ $ncr->kategori_ketidaksesuaian }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Status:</td>
                            <td>{{ $ncr->status_ncr }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Estimasi:</td>
                            <td>{{ $ncr->estimasi }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Tindak Lanjut:</td>
                            <td>{{ $ncr->tindak_lanjut }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Dibuat:</td>
                            <td>{{ $ncr->created_at ? $ncr->created_at->format('Y-m-d H:i') : '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>
    @endforeach
</body>


</html>