<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accident / Incident Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .form-container {
            border: 1px solid black;
            padding: 20px;
            background-color: #fff;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid black !important;
            padding: 4px;
            vertical-align: top;
            word-wrap: break-word;
            word-break: break-word;
        }

        .section-title {
            font-weight: bold;
            font-size: 14px;
            margin-top: 20px;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        .check-icon {
            font-size: 14px;
            margin-right: 5px;
        }

        .wrap-text {
            white-space: normal !important;
            word-wrap: break-word;
            word-break: break-word;
        }

        @media print {
            body {
                font-size: 12px !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .container,
            .form-container {
                padding: 10px !important;
                margin: 0px auto !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .no-print {
                display: none !important;
            }

            .table {
                font-size: 12px;
            }

            .section-title {
                font-size: 13px;
            }

            img.logo {
                max-width: 80px !important;
                height: auto !important;
            }
        }
    </style>
</head>

<body>
    <div class="container my-4">
        <div class="form-container">
            <div class="d-flex justify-content-between align-items-start">
                @if (request()->routeIs('adminsystem.incident.downloadSatuan'))
                <img src="{{ public_path('assets/img/logodus.png') }}" alt="Logo" class="logo">
                @else
                <img src="{{ asset('assets/img/logodus.png') }}" alt="Logo" class="logo">
                @endif

                <div style="flex: 1; margin-left: 20px;">
                    <h4 class="text-center">Accident / Incident Report</h4>
                    <table class="table table-bordered mb-0">
                        <tr>
                            <td>No: FRM-HSE-013.1</td>
                            <td>Rev.: 03</td>
                        </tr>
                        <tr>
                            <td>Tanggal: {{ \Carbon\Carbon::parse($incident->created_at)->format('d/m/Y') }}</td>
                            <td>Hal.: 1 of 2</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p class="mt-3 wrap-text"><b>No Laporan:</b> {{ $incident->no_laporan }} &nbsp;&nbsp; <b>Tanggal Pelaporan:</b> {{ \Carbon\Carbon::parse($incident->shift_date)->translatedFormat('l, d F Y') }}</p>

            <div class="section-title">KLASIFIKASI KEJADIAN:</div>
            <div class="row">
                @php
                $klasifikasiList = [
                'Near Miss', 'Illness/Sick', 'First Aid Case (FAC)', 'Medical Treatment Case (MTC)',
                'Restricted Work Case (RWC)', 'Lost Workdays Case (LWC)', 'Permanent Partial Disability (PPD)',
                'Permanent Total Disability (PTD)', 'Fatality', 'Fire Incident', 'Road Incident',
                'Property loss/damage', 'Environmental incident'
                ];
                @endphp
                @foreach($klasifikasiList as $index => $item)
                <div class="col-md-4 wrap-text mb-1">
                    {!! $incident->klasifikasi_kejadiannya === $item
                    ? '<i class="bi bi-check-square-fill check-icon"></i>'
                    : '<i class="bi bi-square check-icon"></i>' !!}
                    {{ $item }}
                </div>
                @endforeach
            </div>

            <div class="section-title">INFORMASI UMUM:</div>
            <p class="wrap-text"><b>Tanggal Kejadian:</b> {{ \Carbon\Carbon::parse($incident->tgl_kejadiannya)->translatedFormat('l, d F Y') }}</p>
            <p class="wrap-text"><b>Jam Kejadian:</b> {{ date('H:i', strtotime($incident->jam_kejadiannya)) }} WIB</p>
            <p class="wrap-text"><b>Lokasi Kejadian:</b> {{ $incident->lokasi_kejadiannya }}</p>
            <p class="wrap-text"><b>Ada Korban:</b> {{ $incident->ada_korban }}</p>

            <div class="section-title">INFORMASI KORBAN:</div>
            <p class="wrap-text"><b>Nama Korban:</b> {{ $incident->nama_korban }}</p>
            <p class="wrap-text"><b>Tanggal Lahir:</b> {{ $incident->tgl_lahir }}</p>
            <p class="wrap-text"><b>Jenis Kelamin:</b> {{ $incident->jenis_kelamin }}</p>
            <p class="wrap-text"><b>Status:</b></p>
            <div class="row">
                @php
                $statusList = ['Karyawan', 'Outsourcing/Borongan', 'Pekerja Borongan', 'Sub Kontraktor', 'Kontraktor', 'Magang', 'Tamu', 'Masyarakat Umum'];
                @endphp
                @foreach($statusList as $item)
                <div class="col-md-4 mb-1 wrap-text">
                    {!! $incident->status === $item
                    ? '<i class="bi bi-check-square-fill check-icon"></i>'
                    : '<i class="bi bi-square check-icon"></i>' !!}
                    {{ $item }}
                </div>
                @endforeach
            </div>
            <p class="wrap-text"><b>Perusahaan:</b> {{ $incident->pers->perusahaan_name  ?? '-'}}</p>
            <p class="wrap-text"><b>Bagian:</b> {{ $incident->bagian }}</p>
            <p class="wrap-text"><b>Jabatan:</b> {{ $incident->jabatan }}</p>

            <div class="section-title">PENJELASAN KEJADIAN DAN TINDAKAN:</div>
            <div class="row">
                <div class="col-md-6">
                    <p class="wrap-text"><b>Jenis Luka/Sakit 1:</b> {{ $incident->jenis_luka_sakit }}</p>
                    <p class="wrap-text"><b>Jenis Luka/Sakit 2:</b> {{ $incident->jenis_luka_sakit2 }}</p>
                    <p class="wrap-text"><b>Jenis Luka/Sakit 3:</b> {{ $incident->jenis_luka_sakit3 }}</p>
                    <p class="wrap-text"><b>Deskripsi Kejadian:</b> {{ $incident->penjelasan_kejadiannya }}</p>
                </div>
                <div class="col-md-6">
                    <p class="wrap-text"><b>Bagian Tubuh 1:</b> {{ $incident->bagian_tubuh_luka }}</p>
                    <p class="wrap-text"><b>Bagian Tubuh 2:</b> {{ $incident->bagian_tubuh_luka2 }}</p>
                    <p class="wrap-text"><b>Bagian Tubuh 3:</b> {{ $incident->bagian_tubuh_luka3 }}</p>
                </div>
            </div>
            <p class="wrap-text"><b>Jenis Kejadian:</b> {{ $incident->jenis_kejadiannya }}</p>
            <p class="wrap-text"><b>Tindakan Pengobatan:</b> {{ $incident->tindakan_pengobatan }}</p>
            <p class="wrap-text"><b>Tindakan Segera Yang Dilakukan:</b> {{ $incident->tindakan_segera_yang_dilakukan }}</p>

            <div class="section-title">ANALISA PENYEBAB MASALAH:</div>
            <p class="wrap-text"><b>Rincian Dari Pemeriksaan:</b> {{ $incident->rincian_dari_pemeriksaan }}</p>

            <b>Kemungkinan Penyebab Langsung:</b>
            <table class="table table-bordered">
                <tr>
                    <td>#1</td>
                    <td>#2</td>
                    <td>#3</td>
                </tr>
                <tr>
                    <td class="wrap-text">{{ $incident->penyebab_langsung_1_a }}</td>
                    <td class="wrap-text">{{ $incident->penyebab_langsung_2_a }}</td>
                    <td class="wrap-text">{{ $incident->penyebab_langsung_3_a }}</td>
                </tr>
                <tr>
                    <td class="wrap-text">{{ $incident->penyebab_langsung_1_b }}</td>
                    <td class="wrap-text">{{ $incident->penyebab_langsung_2_b }}</td>
                    <td class="wrap-text">{{ $incident->penyebab_langsung_3_b }}</td>
                </tr>
            </table>

            <b>Kemungkinan Penyebab Dasar:</b>
            <table class="table table-bordered">
                <tr>
                    <td>#1</td>
                    <td>#2</td>
                    <td>#3</td>
                </tr>
                <tr>
                    <td class="wrap-text">{{ $incident->penyebab_dasar_1_a }}</td>
                    <td class="wrap-text">{{ $incident->penyebab_dasar_2_a }}</td>
                    <td class="wrap-text">{{ $incident->penyebab_dasar_3_a }}</td>
                </tr>
                <tr>
                    <td class="wrap-text">{{ $incident->penyebab_dasar_1_b }}</td>
                    <td class="wrap-text">{{ $incident->penyebab_dasar_2_b }}</td>
                    <td class="wrap-text">{{ $incident->penyebab_dasar_3_b }}</td>
                </tr>
                <tr>
                    <td class="wrap-text">{{ $incident->penyebab_dasar_1_c }}</td>
                    <td class="wrap-text">{{ $incident->penyebab_dasar_2_c }}</td>
                    <td class="wrap-text">{{ $incident->penyebab_dasar_3_c }}</td>
                </tr>
            </table>

            <b>AREA KENDALI UNTUK TINDAKAN PENINGKATAN:</b>
            <table class="table table-bordered">
                <tr>
                    <td>#1</td>
                    <td>#2</td>
                    <td>#3</td>
                </tr>
                <tr>
                    <td class="wrap-text">{{ $incident->tindakan_kendali_untuk_peningkatan_1_a }}</td>
                    <td class="wrap-text">{{ $incident->tindakan_kendali_untuk_peningkatan_2_a }}</td>
                    <td class="wrap-text">{{ $incident->tindakan_kendali_untuk_peningkatan_3_a }}</td>
                </tr>
                <tr>
                    <td class="wrap-text">{{ $incident->tindakan_kendali_untuk_peningkatan_1_b }}</td>
                    <td class="wrap-text">{{ $incident->tindakan_kendali_untuk_peningkatan_2_b }}</td>
                    <td class="wrap-text">{{ $incident->tindakan_kendali_untuk_peningkatan_3_b }}</td>
                </tr>
                <tr>
                    <th>Deskripsi Tindakan Kendali</th>
                    <th>PIC</th>
                    <th>Waktu</th>
                </tr>
                <tr>
                    <td class="wrap-text">1. {{ $incident->deskripsi_tindakan_pencegahan_1 }}</td>
                    <td>{{ $incident->pic_1 }}</td>
                    <td>{{ $incident->timing_1 }}</td>
                </tr>
                <tr>
                    <td class="wrap-text">2. {{ $incident->deskripsi_tindakan_pencegahan_2 }}</td>
                    <td>{{ $incident->pic_2 }}</td>
                    <td>{{ $incident->timing_2 }}</td>
                </tr>
                <tr>
                    <td class="wrap-text">3. {{ $incident->deskripsi_tindakan_pencegahan_3 }}</td>
                    <td>{{ $incident->pic_3 }}</td>
                    <td>{{ $incident->timing_3 }}</td>
                </tr>
            </table>

            <div class="section-title">TANDA TANGAN:</div>
            <p class="wrap-text"><b>Dilaporkan oleh:</b> {{ $incident->inspectors->name }}</p>
            <p><b>Team Investigasi:</b> __________</p>
            <p><b>Mengetahui:</b> __________</p>

            @if (!request()->routeIs('operator.incident.downloadSatuan'))
            <div class="no-print d-flex justify-content-end gap-2 mt-4">
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
            @endif
        </div>
    </div>
</body>

</html>