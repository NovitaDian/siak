<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SentIncident;
use Carbon\Carbon;
use Faker\Factory as Faker;

class IncidentFixSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');
        $shifts = ['Shift 1', 'Shift 2', 'Shift 3', 'Nonshift'];
        $klasifikasiList = [
            'Lost Workdays Case (LWC)',
            'Permanent Partial Disability (PPD)',
            'Permanent Total Disability (PTD)',
            'First Aid Case (FAC)',
            'Medical Treatment Case (MTC)',
            'Restricted Work Case (RWC)',
            'Fire Incident',
            'Road Incident',
            'Near Miss'
        ];

        for ($i = 0; $i < 20; $i++) {
            $shift_date = $faker->dateTimeBetween('-1 year', 'now');
            $klasifikasi = $faker->randomElement($klasifikasiList);
            $workforce = $faker->numberBetween(10, 100);

            $lta = in_array($klasifikasi, ['Lost Workdays Case (LWC)', 'Permanent Partial Disability (PPD)', 'Permanent Total Disability (PTD)']) ? 1 : 0;
            $wlta = in_array($klasifikasi, ['First Aid Case (FAC)', 'Medical Treatment Case (MTC)', 'Restricted Work Case (RWC)']) ? 1 : 0;
            $trc = $lta + $wlta;

            SentIncident::create([
                'stamp_date' => Carbon::now()->format('Y-m-d'),
                'shift_date' => $shift_date->format('Y-m-d'),
                'shift' => $faker->randomElement($shifts),
                'hse_inspector_id' => 1, // pastikan ID 1 ada
                'klasifikasi_kejadiannya' => $klasifikasi,
                'lta' => $lta,
                'wlta' => $wlta,
                'trc' => $trc,
                'man_hours_per_day' => $workforce * 8,
                'total_work_force' => $workforce,
                'total_lta_by_year' => 0,
                'total_wlta_by_year' => 0,
                'total_man_hours' => 0,
                'safe_shift' => $faker->boolean,
                'safe_day' => $faker->boolean,
                'total_safe_day_by_year' => 0,
                'total_safe_day_lta2' => 0,
                'safe_shift_wlta' => $faker->boolean,
                'safe_day_wlta' => $faker->boolean,
                'total_safe_day_wlta' => 0,
                'total_man_hours_lta' => 0,
                'total_man_hours_wlta2' => 0,
                'user_id' => 1, // pastikan user ID 1 ada
                'status_request' => 'Nothing', 
                'status_kejadian' => 'Tidak', 
                'no_laporan' => $faker->unique()->numberBetween(10, 100),
            ]);
        }
    }
}
