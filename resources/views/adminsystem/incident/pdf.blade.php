<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Incident</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #999; padding: 4px; text-align: left; vertical-align: top; }
        th { background-color: #eee; }
        h2 { text-align: center; }
    </style>
</head>
<body>

<h2>Laporan Insiden Kerja</h2>

@foreach($incidents as $incident)
    <table>
        <tr><th>ID</th><td>{{ $incident->id }}</td></tr>
        <tr><th>Writer</th><td>{{ $incident->writer }}</td></tr>
        <tr><th>Tanggal Laporan</th><td>{{ $incident->stamp_date }}</td></tr>
        <tr><th>Shift Date</th><td>{{ $incident->shift_date }}</td></tr>
        <tr><th>Shift</th><td>{{ $incident->shift }}</td></tr>
        <tr><th>HSE Inspector</th><td>{{ $incident->safety_officer_1 }}</td></tr>
        <tr><th>Status Kejadian</th><td>{{ $incident->status_kejadian }}</td></tr>
        <tr><th>Tanggal Kejadian</th><td>{{ $incident->tgl_kejadiannya }}</td></tr>
        <tr><th>Jam Kejadian</th><td>{{ $incident->jam_kejadiannya }}</td></tr>
        <tr><th>Lokasi Kejadian</th><td>{{ $incident->lokasi_kejadiannya }}</td></tr>
        <tr><th>Klasifikasi</th><td>{{ $incident->klasifikasi_kejadiannya }}</td></tr>
        <tr><th>Ada Korban</th><td>{{ $incident->ada_korban }}</td></tr>
        <tr><th>Nama Korban</th><td>{{ $incident->nama_korban }}</td></tr>
        <tr><th>Status</th><td>{{ $incident->status }}</td></tr>
        <tr><th>Jenis Kelamin</th><td>{{ $incident->jenis_kelamin }}</td></tr>
        <tr><th>Perusahaan</th><td>{{ $incident->perusahaan }}</td></tr>
        <tr><th>Bagian</th><td>{{ $incident->bagian }}</td></tr>
        <tr><th>Jabatan</th><td>{{ $incident->jabatan }}</td></tr>
        <tr><th>Masa Kerja</th><td>{{ $incident->masa_kerja }}</td></tr>
        <tr><th>Tgl Lahir</th><td>{{ $incident->tgl_lahir }}</td></tr>
        <tr><th>Jenis Luka Sakit</th><td>{{ $incident->jenis_luka_sakit }} {{ $incident->jenis_luka_sakit2 }} {{ $incident->jenis_luka_sakit3 }}</td></tr>
        <tr><th>Bagian Tubuh Luka</th><td>{{ $incident->bagian_tubuh_luka }} {{ $incident->bagian_tubuh_luka2 }} {{ $incident->bagian_tubuh_luka3 }}</td></tr>
        <tr><th>Jenis Kejadian</th><td>{{ $incident->jenis_kejadiannya }}</td></tr>
        <tr><th>Penjelasan Kejadian</th><td>{{ $incident->penjelasan_kejadiannya }}</td></tr>
        <tr><th>Tindakan Pengobatan</th><td>{{ $incident->tindakan_pengobatan }}</td></tr>
        <tr><th>Tindakan Segera</th><td>{{ $incident->tindakan_segera_yang_dilakukan }}</td></tr>
        <tr><th>Rincian Pemeriksaan</th><td>{{ $incident->rincian_dari_pemeriksaan }}</td></tr>

        <!-- Penyebab Langsung -->
        <tr><th>Penyebab Langsung</th>
            <td>
                {{ $incident->penyebab_langsung_1_a }}<br>
                {{ $incident->penyebab_langsung_1_b }}<br>
                {{ $incident->penyebab_langsung_2_a }}<br>
                {{ $incident->penyebab_langsung_2_b }}<br>
                {{ $incident->penyebab_langsung_3_a }}<br>
                {{ $incident->penyebab_langsung_3_b }}
            </td>
        </tr>

        <!-- Penyebab Dasar -->
        <tr><th>Penyebab Dasar</th>
            <td>
                {{ $incident->penyebab_dasar_1_a }}<br>
                {{ $incident->penyebab_dasar_1_b }}<br>
                {{ $incident->penyebab_dasar_1_c }}<br>
                {{ $incident->penyebab_dasar_2_a }}<br>
                {{ $incident->penyebab_dasar_2_b }}<br>
                {{ $incident->penyebab_dasar_2_c }}<br>
                {{ $incident->penyebab_dasar_3_a }}<br>
                {{ $incident->penyebab_dasar_3_b }}<br>
                {{ $incident->penyebab_dasar_3_c }}
            </td>
        </tr>

        <!-- Tindakan Pencegahan -->
        <tr><th>Tindakan Pencegahan 1</th>
            <td>
                {{ $incident->tindakan_kendali_untuk_peningkatan_1_a }}<br>
                {{ $incident->tindakan_kendali_untuk_peningkatan_1_b }}<br>
                {{ $incident->deskripsi_tindakan_pencegahan_1 }}<br>
                PIC: {{ $incident->pic_1 }}, Timing: {{ $incident->timing_1 }}
            </td>
        </tr>
        <tr><th>Tindakan Pencegahan 2</th>
            <td>
                {{ $incident->tindakan_kendali_untuk_peningkatan_2_a }}<br>
                {{ $incident->tindakan_kendali_untuk_peningkatan_2_b }}<br>
                {{ $incident->deskripsi_tindakan_pencegahan_2 }}<br>
                PIC: {{ $incident->pic_2 }}, Timing: {{ $incident->timing_2 }}
            </td>
        </tr>
        <tr><th>Tindakan Pencegahan 3</th>
            <td>
                {{ $incident->tindakan_kendali_untuk_peningkatan_3_a }}<br>
                {{ $incident->tindakan_kendali_untuk_peningkatan_3_b }}<br>
                {{ $incident->deskripsi_tindakan_pencegahan_3 }}<br>
                PIC: {{ $incident->pic_3 }}, Timing: {{ $incident->timing_3 }}
            </td>
        </tr>

        <!-- Statistik -->
        <tr><th>Statistik</th>
            <td>
                Employee: {{ $incident->jml_employee }},
                Outsource: {{ $incident->jml_outsources }},
                Security: {{ $incident->jml_security }},
                Loading/Stacking: {{ $incident->jml_loading_stacking }},
                Contractor: {{ $incident->jml_contractor }},
                Hari Hilang: {{ $incident->jml_hari_hilang }},
                Bulan/Tahun: {{ $incident->bulan_tahun }}
            </td>
        </tr>

        <!-- Safety Performance -->
        <tr><th>Data Safety</th>
            <td>
                Near Miss: {{ $incident->near_miss }},
                Illness/Sick: {{ $incident->illness_sick }},
                First Aid: {{ $incident->first_aid_case }},
                Medical Treatment: {{ $incident->medical_treatment_case }},
                Restricted Work: {{ $incident->restricted_work_case }},
                Lost Workdays: {{ $incident->lost_workdays_case }},
                Fatality: {{ $incident->fatality }},
                LTA: {{ $incident->lta }},
                WLTA: {{ $incident->wlta }},
                TRC: {{ $incident->trc }},
                Fire: {{ $incident->fire_incident }},
                Road: {{ $incident->road_incident }},
                Property Loss: {{ $incident->property_loss_damage }},
                Environmental: {{ $incident->environmental_incident }}
            </td>
        </tr>

        <!-- Safe Days -->
        <tr><th>Safe Time</th>
            <td>
                Safe Shift: {{ $incident->safe_shift }},
                Safe Day: {{ $incident->safe_day }},
                Total Safe Day This Year: {{ $incident->total_safe_day_by_year }},
                Total Man Hours: {{ $incident->total_man_hours }}
            </td>
        </tr>
    </table>
@endforeach

</body>
</html>
