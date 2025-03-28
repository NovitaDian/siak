<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accident / Incident Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header-table,
        .content-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            padding: 5px;
        }

        .header-table td:first-child {
            width: 60%;
        }

        .header-table td:last-child {
            width: 40%;
        }

        .content-table td {
            padding: 10px;
            vertical-align: top;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
        }

        .bordered {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <table class="header-table">
        @forelse ($incidents as $incident)
        <tr>
            <td>
                <h3>PT. DUS</h3>
                <h2>Accident / Incident Report</h2>
            </td>
            <td>
                <table>
                    <tr>
                        <td>No:</td>
                        <td>FRM-HSE-013.1</td>
                    </tr>
                    <tr>
                        <td>Rev.:</td>
                        <td>03</td>
                    </tr>
                    <tr>
                        <td>Tgl.:</td>
                    </tr>
                    <tr>
                        <td>Hal.:</td>
                        <td>1</td>
                    </tr>
                </table>
            </td>
        </tr>
        @empty
        <p>No incident data available.</p>
        @endforelse
    </table>
    <hr>

    @forelse ($incidents as $incident)
    <p><strong>NO LAPORAN:</strong> {{ isset($incident->no_laporan) ? $incident->no_laporan : 'Data tidak tersedia' }}</p>
    <p><strong>Tanggal Pelaporan:</strong> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    <p><strong>Dilaporkan oleh:</strong> {{ Auth::user()->name }}</p>

    <div class="section-title">Tindakan Pengobatan:</div>
    <p>Tindakan Segera Yang Dilakukan: {{ isset($incident->tindakan_segera_yang_dilakukan) ? $incident->tindakan_segera_yang_dilakukan : 'Data tidak tersedia' }}</p>

    <div class="section-title">ANALISA PENYEBAB MASALAH:</div>
    <p>Rincian Dari Pemeriksaan: {{ isset($incident->rincian_dari_pemeriksaan) ? $incident->rincian_dari_pemeriksaan : 'Data tidak tersedia' }}</p>

    <div class="section-title">Kemungkinan Penyebab Langsung:</div>
    <ol>
        <li>{{ isset($incident->penyebab_langsung_1_a) ? $incident->penyebab_langsung_1_a : 'Data tidak tersedia' }}</li>
        <li>{{ isset($incident->penyebab_langsung_2_a) ? $incident->penyebab_langsung_2_a : 'Data tidak tersedia' }}</li>
        <li>{{ isset($incident->penyebab_langsung_3_a) ? $incident->penyebab_langsung_3_a : 'Data tidak tersedia' }}</li>
    </ol>

    <div class="section-title">Kemungkinan Penyebab Dasar:</div>
    <ol>
        <li>{{ isset($incident->penyebab_dasar_1_a) ? $incident->penyebab_dasar_1_a : 'Data tidak tersedia' }}</li>
        <li>{{ isset($incident->penyebab_dasar_2_a) ? $incident->penyebab_dasar_2_a : 'Data tidak tersedia' }}</li>
        <li>{{ isset($incident->penyebab_dasar_3_a) ? $incident->penyebab_dasar_3_a : 'Data tidak tersedia' }}</li>
    </ol>

    <div class="section-title">AREA KENDALI UNTUK TINDAKAN PENINGKATAN:</div>
    <table class="content-table bordered">
        <tr>
            <th>No.</th>
            <th>Deskripsi Tindakan Kendali</th>
            <th>PIC</th>
            <th>Waktu</th>
        </tr>
        <tr>
            <td>1.</td>
            <td>{{ isset($incident->tindakan_kendali_untuk_peningkatan_1_a) ? $incident->tindakan_kendali_untuk_peningkatan_1_a : 'Data tidak tersedia' }}</td>
            <td>{{ isset($incident->pic_1) ? $incident->pic_1 : 'Data tidak tersedia' }}</td>
            <td>{{ isset($incident->timing_1) ? $incident->timing_1 : 'Data tidak tersedia' }}</td>
        </tr>
        <tr>
            <td>2.</td>
            <td>{{ isset($incident->tindakan_kendali_untuk_peningkatan_2_a) ? $incident->tindakan_kendali_untuk_peningkatan_2_a : 'Data tidak tersedia' }}</td>
            <td>{{ isset($incident->pic_2) ? $incident->pic_2 : 'Data tidak tersedia' }}</td>
            <td>{{ isset($incident->timing_2) ? $incident->timing_2 : 'Data tidak tersedia' }}</td>
        </tr>
        <tr>
            <td>3.</td>
            <td>{{ isset($incident->tindakan_kendali_untuk_peningkatan_3_a) ? $incident->tindakan_kendali_untuk_peningkatan_3_a : 'Data tidak tersedia' }}</td>
            <td>{{ isset($incident->pic_3) ? $incident->pic_3 : 'Data tidak tersedia' }}</td>
            <td>{{ isset($incident->timing_3) ? $incident->timing_3 : 'Data tidak tersedia' }}</td>
        </tr>
    </table>

    <div class="section-title">Dilaporkan oleh:</div>
    <p>{{ Auth::user()->name }}</p>
    <p>Tanda Tangan: ___________________</p>
    <p>Tanggal: ___________________</p>

    <div class="section-title">Team Investigasi:</div>
    <p>Tanda Tangan: ___________________</p>
    <p>Tanggal: ___________________</p>

    <div class="section-title">Mengetahui:</div>
    <p>Tanda Tangan: ___________________</p>
    <p>Tanggal: ___________________</p>
    @empty
    <p>No incident data available.</p>
    @endforelse
</body>

</html>
