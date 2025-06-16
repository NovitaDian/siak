<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncidentFixSeeder extends Seeder
{
    public function run()
    {
        $file = database_path('seeders/data/incident.csv');

        if (!file_exists($file) || !is_readable($file)) {
            $this->command->error("Gagal membuka file CSV: $file");
            return;
        }

        $handle = fopen($file, 'r');

        $headers = [
            'writer',
            'stamp_date',
            'shift_date',
            'shift',
            'safety_officer_1',
            'status_kejadian',
            'ada_korban',
            'tgl_kejadiannya',
            'jam_kejadiannya',
            'lokasi_kejadiannya',
            'klasifikasi_kejadiannya',
            'ada',
            'nama_korban',
            'status',
            'jenis_kelamin',
            'perusahaan',
            'bagian',
            'jabatan',
            'masa_kerja',
            'tgl_lahir',
            'jenis_luka_sakit',
            'jenis_luka_sakit2',
            'jenis_luka_sakit3',
            'bagian_tubuh_luka',
            'bagian_tubuh_luka2',
            'bagian_tubuh_luka3',
            'jenis_kejadiannya',
            'penjelasan_kejadiannya',
            'tindakan_pengobatan',
            'tindakan_segera_yang_dilakukan',
            'rincian_dari_pemeriksaan',
            'penyebab_langsung_1_a',
            'penyebab_langsung_1_b',
            'penyebab_langsung_2_a',
            'penyebab_langsung_2_b',
            'penyebab_langsung_3_a',
            'penyebab_langsung_3_b',
            'penyebab_dasar_1_a',
            'penyebab_dasar_1_b',
            'penyebab_dasar_1_c',
            'penyebab_dasar_2_a',
            'penyebab_dasar_2_b',
            'penyebab_dasar_2_c',
            'penyebab_dasar_3_a',
            'penyebab_dasar_3_b',
            'penyebab_dasar_3_c',
            'tindakan_kendali_untuk_peningkatan_1_a',
            'tindakan_kendali_untuk_peningkatan_1_b',
            'tindakan_kendali_untuk_peningkatan_1_c',
            'deskripsi_tindakan_pencegahan_1',
            'pic_1',
            'timing_1',
            'tindakan_kendali_untuk_peningkatan_2_a',
            'tindakan_kendali_untuk_peningkatan_2_b',
            'tindakan_kendali_untuk_peningkatan_2_c',
            'deskripsi_tindakan_pencegahan_2',
            'pic_2',
            'timing_2',
            'tindakan_kendali_untuk_peningkatan_3_a',
            'tindakan_kendali_untuk_peningkatan_3_b',
            'tindakan_kendali_untuk_peningkatan_3_c',
            'deskripsi_tindakan_pencegahan_3',
            'pic_3',
            'timing_3',
            'jml_employee',
            'jml_outsources',
            'jml_security',
            'jml_loading_stacking',
            'jml_contractor',
            'jml_hari_hilang',
            'no_laporan',
            'lta',
            'wlta',
            'trc',
            'total_lta_by_year',
            'total_wlta_by_year',
            'total_work_force',
            'man_hours_per_day',
            'total_man_hours',
            'safe_shift',
            'safe_day',
            'total_safe_day_by_year',
            'total_safe_day_lta2',
            'total_man_hours_lta',
            'total_man_hours_wlta2',
            'safe_shift_wlta',
            'safe_day_wlta',
            'total_safe_day_wlta',
            'status_request',
            'user_id',
        ];

        $numericFields = [
            'masa_kerja',
            'jml_employee',
            'jml_outsources',
            'jml_security',
            'jml_loading_stacking',
            'jml_contractor',
            'jml_hari_hilang',
            'lta',
            'wlta',
            'trc',
            'total_lta_by_year',
            'total_wlta_by_year',
            'total_work_force',
            'man_hours_per_day',
            'total_man_hours',
            'safe_shift',
            'safe_day',
            'total_safe_day_by_year',
            'total_safe_day_lta2',
            'total_man_hours_lta',
            'total_man_hours_wlta2',
            'safe_shift_wlta',
            'safe_day_wlta',
            'total_safe_day_wlta',
            'timing_1',
            'timing_2',
            'timing_3',
        ];


        $tanggalFields = [
            'stamp_date',
            'shift_date',
            'tgl_kejadiannya',
            'tgl_lahir',
        ];
        $draftId = 1;

        $rowNumber = 1;
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $rowNumber++;

            if (count($row) !== count($headers)) {
                $this->command->warn("Lewati baris $rowNumber: jumlah kolom tidak cocok.");
                continue;
            }

            $rowData = array_combine($headers, $row);


            $rowData['draft_id'] = $draftId++;


            // Format dan validasi kolom tanggal
            foreach ($tanggalFields as $field) {
                if (empty($rowData[$field])) {
                    $rowData[$field] = null;
                } else {
                    $rowData[$field] = date('Y-m-d', strtotime(str_replace('/', '-', $rowData[$field])));
                }
            }
            foreach ($numericFields as $field) {
                if (isset($rowData[$field])) {
                    $cleaned = str_replace('.', '', $rowData[$field]);
                    $rowData[$field] = is_numeric($cleaned) ? (int) $cleaned : null;
                }
            }
            if (empty($rowData['masa_kerja'])) {
                $rowData['masa_kerja'] = null;
            }
            $rowData['created_at'] = now();
            $rowData['updated_at'] = now();
            DB::table('incidents_fix')->insert($rowData);
        }

        fclose($handle);
        $this->command->info("Data berhasil diimpor.");
    }
}
