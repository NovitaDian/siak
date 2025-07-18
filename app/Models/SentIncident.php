<?php

namespace App\Models;

use App\Models\IncidentRequest as ModelsIncidentRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentIncident extends Model
{
    use HasFactory;
    protected $table = 'incidents_fix';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'user_id',

        'stamp_date',
        'shift_date',
        'shift',
        'hse_inspector_id',
        'status_kejadian',
        'tgl_kejadiannya',
        'jam_kejadiannya',
        'lokasi_kejadiannya',
        'klasifikasi_kejadiannya',
        'ada_korban',
        'nama_korban',
        'status',
        'jenis_kelamin',
        'perusahaan_id',
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
        'urut_kejadiannya',
        'tanggal_urut_kejadiannya',
        'status_request',
    ];

    // Cast tipe data agar otomatis konversi saat akses dan penyimpanan
    protected $casts = [
        'stamp_date' => 'date',
        'shift_date' => 'date',
        'tgl_kejadiannya' => 'date',
        'jam_kejadiannya' => 'string', // karena di DB pakai time
        'tgl_lahir' => 'date',

        // Boolean fields
        'ada_korban' => 'boolean',
        'lta' => 'boolean',
        'wlta' => 'boolean',
        'trc' => 'boolean',
        'safe_shift' => 'boolean',
        'safe_day' => 'boolean',

        // Integer fields
        'masa_kerja' => 'integer',
        'jml_employee' => 'integer',
        'jml_outsources' => 'integer',
        'jml_security' => 'integer',
        'jml_loading_stacking' => 'integer',
        'jml_contractor' => 'integer',
        'jml_hari_hilang' => 'integer',
        'total_lta_by_year' => 'integer',
        'total_wlta_by_year' => 'integer',
        'total_work_force' => 'integer',
        'man_hours_per_day' => 'integer',
        'total_man_hours' => 'integer',
        'total_safe_day_by_year' => 'integer',
        'total_safe_day_lta2' => 'integer',
        'total_man_hours_lta' => 'integer',
        'total_man_hours_wlta2' => 'integer',
        'safe_shift_wlta' => 'integer',
        'safe_day_wlta' => 'integer',
        'total_safe_day_wlta' => 'integer',
        'urut_kejadiannya' => 'integer',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function inspectors()
    {
        return $this->belongsTo(HseInspector::class, 'hse_inspector_id');
    }
    public function pers()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }
}
