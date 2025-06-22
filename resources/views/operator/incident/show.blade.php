<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accident / Incident Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .form-container {
            border: 1px solid black;
            padding: 20px;
        }

        .table-bordered {
            border: 1px solid black;
        }

        .logo {
            width: 100px;
            height: 100px;
        }

        .section-title {
            font-weight: bold;
            font-size: 18px;
            text-decoration: underline;
        }

        .checkbox {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 1px solid black;
            text-align: center;
        }

        .checked {
            background-color: black;
        }

        @media print {
            body {
                font-size: 14px !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .container,
            .form-container {
                padding: 10px !important;
                margin: 0px !important;
                width: 150% !important;
            }

            .no-print {
                display: none !important;
            }

            table {
                font-size: 14px !important;
            }
        }
    </style>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .title {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div class="container my-4">
        <div class="form-container">
            <div class="d-flex justify-content-between">
                @if (request()->routeIs('adminsystem.incident.downloadSatuan'))
                {{-- Saat PDF --}}
                <img src="{{ public_path('assets/img/logodus.png') }}" style="width: 100px; height: 100px;">
                @else
                {{-- Saat ditampilkan di web --}}
                <img src="{{ asset('assets/img/logodus.png') }}" style="width: 100px; height: 100px;">
                @endif
                <div>
                    <h4 class="text-center">Accident / Incident Report</h4>
                    <table class="table table-bordered">
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

            <p><b>No Laporan:</b> {{ $incident->no_laporan }} &nbsp;&nbsp; <b>Tanggal Pelaporan:</b> {{ \Carbon\Carbon::parse($incident->shift_date)->translatedFormat('l, d F Y') }}</p>

            <div class="section-title">KLASIFIKASI KEJADIAN:</div>
            <div class="row">
                <div class="col-md-4">
                    <div>
                        <input type="checkbox" name="klasifikasi_kejadiannya[]" value="Near Miss"
                            <?= $incident->klasifikasi_kejadiannya == "Near Miss" ? "checked" : "" ?>>
                        Near Miss
                    </div>
                    <div>
                        <input type="checkbox" name="klasifikasi_kejadiannya[]" value="Illness/Sick"
                            <?= $incident->klasifikasi_kejadiannya == "Illness/Sick" ? "checked" : "" ?>>
                        Illness/Sick
                    </div>
                    <div>
                        <input type="checkbox" name="klasifikasi_kejadiannya[]" value="First Aid Case (FAC)"
                            <?= $incident->klasifikasi_kejadiannya == "First Aid Case (FAC)" ? "checked" : "" ?>>
                        First Aid Case (FAC)
                    </div>
                    <div>
                        <input type="checkbox" name="klasifikasi_kejadiannya[]" value="Medical Treatment Case (MTC)"
                            <?= $incident->klasifikasi_kejadiannya == "Medical Treatment Case (MTC)" ? "checked" : "" ?>>
                        Medical Treatment Case (MTC)
                    </div>
                    <div>
                        <input type="checkbox" name="klasifikasi_kejadiannya[]" value="Restricted Work Case (RWC)"
                            <?= $incident->klasifikasi_kejadiannya == "Restricted Work Case (RWC)" ? "checked" : "" ?>>
                        Restricted Work Case (RWC)
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <input type="checkbox" name="klasifikasi_kejadiannya[]" value="Lost Workdays Case (LWC)"
                            <?= $incident->klasifikasi_kejadiannya == "Lost Workdays Case (LWC)" ? "checked" : "" ?>>
                        Lost Workdays Case (LWC)
                    </div>
                    <div>
                        <input type="checkbox" name="klasifikasi_kejadiannya[]" value="Permanent Partial Disability (PTD)"
                            <?= $incident->klasifikasi_kejadiannya == "Permanent Partial Disability (PTD)" ? "checked" : "" ?>>
                        Permanent Partial Disability (PPD)
                    </div>
                    <div>
                        <input type="checkbox" name="klasifikasi_kejadiannya[]" value="Permanent Total Disability (PTD)"
                            <?= $incident->klasifikasi_kejadiannya == "Permanent Total Disability (PTD)" ? "checked" : "" ?>>
                        Permanent Total Disability (PTD)
                    </div>
                    <div>
                        <input type="checkbox" name="klasifikasi_kejadiannya[]" value="Fatality"
                            <?= $incident->klasifikasi_kejadiannya == "Fatality" ? "checked" : "" ?>>
                        Fatality
                    </div>
                    <div>
                        <input type="checkbox" name="klasifikasi_kejadiannya[]" value="Fire Incident"
                            <?= $incident->klasifikasi_kejadiannya == "Fire Incident" ? "checked" : "" ?>>
                        Fire Incident
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <input type="checkbox" name="klasifikasi_kejadiannya[]" value="Road Incident"
                            <?= $incident->klasifikasi_kejadiannya == "Road Incident" ? "checked" : "" ?>>
                        Road Incident
                    </div>
                    <div>
                        <input type="checkbox" name="klasifikasi_kejadiannya[]" value="Property loss/damage"
                            <?= $incident->klasifikasi_kejadiannya == "Property loss/damage" ? "checked" : "" ?>>
                        Property loss/damage
                    </div>
                    <div>
                        <input type="checkbox" name="klasifikasi_kejadiannya[]" value="Environmental incident"
                            <?= $incident->klasifikasi_kejadiannya == "Environmental incident" ? "checked" : "" ?>>
                        Environmental incident
                    </div>
                </div>
            </div>


            <div class="section-title">INFORMASI UMUM:</div>
            <p><b>Tanggal Kejadian:</b> {{ date('l, d F Y', strtotime($incident->tgl_kejadiannya)) }}</p>
            <p><b>Jam Kejadian:</b> {{ date('H:i', strtotime($incident->jam_kejadiannya)) }} WIB</p>
            <p><b>Lokasi Kejadian:</b> {{ $incident->lokasi_kejadiannya }}</p>
            <p><b>Ada Korban:</b> {{ $incident->ada_korban }}</p>


            <div class="section-title">INFORMASI KORBAN:</div>
            <p><b>Nama Korban:</b> {{ $incident->nama_korban }}</p>
            <p><b>Tanggal Lahir:</b> {{ $incident->tgl_lahir }}</p>
            <p><b>Nama Korban:</b> {{ $incident->jenis_kelamin }}</p>
            <p><b>Status: </b></p>
            <div class="row">
                <div class="col-md-6">
                    <div>
                        <input type="checkbox" name="status[]" value="Karyawan"
                            <?= $incident->status == "Karyawan" ? "checked" : "" ?>>
                        Karyawan
                    </div>
                    <div>
                        <input type="checkbox" name="status[]" value="Outsourcing/Borongan"
                            <?= $incident->status == "Outsourcing/Borongan" ? "checked" : "" ?>>
                        Outsourcing/Borongan
                    </div>
                    <div>
                        <input type="checkbox" name="status[]" value="Pekerja Borongan"
                            <?= $incident->status == "Pekerja Borongan" ? "checked" : "" ?>>
                        Pekerja Borongan
                    </div>
                    <div>
                        <input type="checkbox" name="status[]" value="Sub Kontraktor"
                            <?= $incident->status == "Sub Kontraktor" ? "checked" : "" ?>>
                        Sub Kontraktor
                    </div>
                    <div>
                        <input type="checkbox" name="status[]" value="Kontraktor"
                            <?= $incident->status == "Kontraktor" ? "checked" : "" ?>>
                        Kontraktor
                    </div>
                </div>
                <div class="col-md-6">
                    <div>
                        <input type="checkbox" name="status[]" value="Magang"
                            <?= $incident->status == "Magang" ? "checked" : "" ?>>
                        Magang
                    </div>
                    <div>
                        <input type="checkbox" name="status[]" value="Tamu"
                            <?= $incident->status == "Tamu" ? "checked" : "" ?>>
                        Tamu
                    </div>
                    <div>
                        <input type="checkbox" name="status[]" value="Masyarakat Umum"
                            <?= $incident->status == "Masyarakat Umum" ? "checked" : "" ?>>
                        Masyarakat Umum
                    </div>
                </div>

                <p><b>Perusahaan:</b> {{ $incident->perusahaan }}</p>
                <p><b>Bagian:</b> {{ $incident->bagian }}</p>
                <p><b>Jabatan:</b> {{ $incident->jabatan }}</p>
                <div class="section-title">PENJELASAN KEJADIAN DAN TINDAKAN:</div>
                <div class="row">
                    <div class="col-md-6">
                        <div><b>Jenis Luka/Sakit 1:</b> {{ $incident->jenis_luka_sakit }}</div>
                        <div><b>Jenis Luka/Sakit 2:</b> {{ $incident->jenis_luka_sakit2 }}</div>
                        <div><b>Jenis Luka/Sakit 3:</b> {{ $incident->jenis_luka_sakit3 }}</div>
                        <div><b>Deskripsi Kejadian:</b> {{ $incident->penjelasan_kejadiannya }}</div>
                    </div>
                    <div class="col-md-6">
                        <div><b>Bagian Tubuh 1:</b> {{ $incident->bagian_tubuh_luka }}</div>
                        <div><b>Bagian Tubuh 2:</b> {{ $incident->bagian_tubuh_luka2 }}</div>
                        <div><b>Bagian Tubuh 3:</b> {{ $incident->bagian_tubuh_luka3 }}</div>
                    </div>
                    <div><b>Jenis Kejadian:</b> {{ $incident->jenis_kejadiannya }}</div>
                    <div><b>Deskripsi Kejadian:</b> {{ $incident->penjelasan_kejadiannya }}</div>
                    <p><b>Tindakan Pengobatan:</b> {{ $incident->tindakan_pengobatan }}</p>
                    <p><b>Tindakan Segera Yang Dilakukan:</b> {{ $incident->tindakan_segera_yang_dilakukan }}</p>
                    <div class="section-title">ANALISA PENYEBAB MASALAH:</div>
                    <p><b>Rincian Dari Pemeriksaan:</b> {{ $incident->rincian_dari_pemeriksaan }}</p>
                    <div><b>Kemungkinan Penyebab Langsung:</b></div>
                    <table class="table table-bordered">
                        <tr>
                            <td>#1</td>
                            <td>#2</td>
                            <td>#3</td>
                        </tr>
                        <tr>
                            <td>{{ $incident->penyebab_langsung_1_a }}</td>
                            <td>{{ $incident->penyebab_langsung_2_a}}</td>
                            <td>{{ $incident->penyebab_langsung_3_a}}</td>
                        </tr>
                        <tr>
                            <td>{{ $incident->penyebab_langsung_1_b}}</td>
                            <td>{{ $incident->penyebab_langsung_2_b}}</td>
                            <td>{{ $incident->penyebab_langsung_3_b}}</td>
                        </tr>
                    </table>
                    <div><b>Kemungkinan Penyebab Dasar:</b></div>
                    <table class="table table-bordered">
                        <tr>
                            <td>#1</td>
                            <td>#2</td>
                            <td>#3</td>
                        </tr>
                        <tr>
                            <td>{{ $incident->penyebab_dasar_1_a }}</td>
                            <td>{{ $incident->penyebab_dasar_2_a}}</td>
                            <td>{{ $incident->penyebab_dasar_3_a}}</td>
                        </tr>
                        <tr>
                            <td>{{ $incident->penyebab_dasar_1_b}}</td>
                            <td>{{ $incident->penyebab_dasar_2_b}}</td>
                            <td>{{ $incident->penyebab_dasar_3_b}}</td>
                        </tr>
                        <tr>
                            <td>{{ $incident->penyebab_dasar_1_c}}</td>
                            <td>{{ $incident->penyebab_dasar_2_c}}</td>
                            <td>{{ $incident->penyebab_dasar_3_c}}</td>
                        </tr>
                    </table>
                    <div><b>AREA KENDALI UNTUK TINDAKAN PENINGKATAN:</b></div>
                    <table class="table table-bordered">
                        <tr>
                            <td>#1</td>
                            <td>#2</td>
                            <td>#3</td>
                        </tr>
                        <tr>
                            <td>{{ $incident->tindakan_kendali_untuk_peningkatan_1_a }}</td>
                            <td>{{ $incident->tindakan_kendali_untuk_peningkatan_2_a}}</td>
                            <td>{{ $incident->tindakan_kendali_untuk_peningkatan_3_a}}</td>
                        </tr>
                        <tr>
                            <td>{{ $incident->tindakan_kendali_untuk_peningkatan_1_b }}</td>
                            <td>{{ $incident->tindakan_kendali_untuk_peningkatan_2_b}}</td>
                            <td>{{ $incident->tindakan_kendali_untuk_peningkatan_3_b}}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi Tindakan Kendali</th>
                            <th>PIC</th>
                            <th>Waktu</th>
                        </tr>
                        <tr>
                            <td>1. {{ $incident->deskripsi_tindakan_pencegahan_1 }}</td>
                            <td>{{ $incident->pic_1 }}</td>
                            <td>{{ $incident->timing_1 }}</td>
                        </tr>
                        <tr>
                            <td>2.{{ $incident->deskripsi_tindakan_pencegahan_2 }}</td>
                            <td>{{ $incident->pic_2 }}</td>
                            <td>{{ $incident->timing_2 }}</td>
                        </tr>
                        <tr>
                            <td>3.{{ $incident->deskripsi_tindakan_pencegahan_3 }}</td>
                            <td>{{ $incident->pic_3 }}</td>
                            <td>{{ $incident->timing_3 }}</td>
                        </tr>
                    </table>

                    <div class="section-title">TANDA TANGAN:</div>
                    <p><b>Dilaporkan oleh:</b>{{ $incident->safety_officer_1 }}</p>
                    <p><b>Team Investigasi:</b> __________</p>
                    <p><b>Mengetahui:</b> __________</p>
                </div>
            </div>
</body>

</html>