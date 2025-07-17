<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incidents_fix', function (Blueprint $table) {
            $table->id();
            $table->dateTime('stamp_date')->nullable();
            $table->date('shift_date');
            $table->string('shift');
            $table->string('status_kejadian')->nullable();
            $table->date('tgl_kejadiannya')->nullable();
            $table->time('jam_kejadiannya')->nullable();
            $table->string('lokasi_kejadiannya')->nullable();
            $table->string('klasifikasi_kejadiannya')->nullable();
            $table->string('ada_korban')->nullable();
            $table->string('ada')->nullable();
            $table->string('nama_korban')->nullable();
            $table->string('status')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->unsignedBigInteger('hse_inspector_id');
            $table->unsignedBigInteger('perusahaan_id')->nullable();
            $table->string('bagian')->nullable();
            $table->string('jabatan')->nullable();
            $table->integer('masa_kerja')->nullable();
            $table->string('tgl_lahir')->nullable(); // sesuai struktur, ini string bukan date
            $table->string('jenis_luka_sakit')->nullable();
            $table->string('jenis_luka_sakit2')->nullable();
            $table->string('jenis_luka_sakit3')->nullable();
            $table->string('bagian_tubuh_luka')->nullable();
            $table->string('bagian_tubuh_luka2')->nullable();
            $table->string('bagian_tubuh_luka3')->nullable();
            $table->text('jenis_kejadiannya')->nullable();
            $table->text('penjelasan_kejadiannya')->nullable();
            $table->text('tindakan_pengobatan')->nullable();
            $table->text('tindakan_segera_yang_dilakukan')->nullable();
            $table->text('rincian_dari_pemeriksaan')->nullable();
            $table->string('penyebab_langsung_1_a')->nullable();
            $table->string('penyebab_langsung_1_b')->nullable();
            $table->string('penyebab_langsung_2_a')->nullable();
            $table->string('penyebab_langsung_2_b')->nullable();
            $table->string('penyebab_langsung_3_a')->nullable();
            $table->string('penyebab_langsung_3_b')->nullable();
            $table->string('penyebab_dasar_1_a')->nullable();
            $table->string('penyebab_dasar_1_b')->nullable();
            $table->string('penyebab_dasar_1_c')->nullable();
            $table->string('penyebab_dasar_2_a')->nullable();
            $table->string('penyebab_dasar_2_b')->nullable();
            $table->string('penyebab_dasar_2_c')->nullable();
            $table->string('penyebab_dasar_3_a')->nullable();
            $table->string('penyebab_dasar_3_b')->nullable();
            $table->string('penyebab_dasar_3_c')->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_1_a')->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_1_b')->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_1_c')->nullable();
            $table->text('deskripsi_tindakan_pencegahan_1')->nullable();
            $table->string('pic_1')->nullable();
            $table->date('timing_1')->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_2_a')->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_2_b')->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_2_c')->nullable();
            $table->text('deskripsi_tindakan_pencegahan_2')->nullable();
            $table->string('pic_2')->nullable();
            $table->string('timing_2')->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_3_a')->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_3_b')->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_3_c')->nullable();
            $table->text('deskripsi_tindakan_pencegahan_3')->nullable();
            $table->string('pic_3')->nullable();
            $table->date('timing_3')->nullable();
            $table->integer('jml_employee')->nullable();
            $table->integer('jml_outsources')->nullable();
            $table->integer('jml_security')->nullable();
            $table->integer('jml_loading_stacking')->nullable();
            $table->integer('jml_contractor')->nullable();
            $table->integer('jml_hari_hilang')->nullable();
            $table->string('no_laporan')->unique();
            $table->tinyInteger('lta')->nullable();
            $table->tinyInteger('wlta')->nullable();
            $table->tinyInteger('trc')->nullable();
            $table->integer('total_lta_by_year')->nullable();
            $table->integer('total_wlta_by_year')->nullable();
            $table->integer('total_work_force')->nullable();
            $table->integer('man_hours_per_day')->nullable();
            $table->integer('total_man_hours')->nullable();
            $table->integer('safe_shift')->nullable();
            $table->integer('safe_day')->nullable();
            $table->integer('total_safe_day_by_year')->nullable();
            $table->integer('total_safe_day_lta2')->nullable();
            $table->integer('total_man_hours_lta')->nullable();
            $table->integer('total_man_hours_wlta2')->nullable();
            $table->integer('safe_shift_wlta')->nullable();
            $table->integer('safe_day_wlta')->nullable();
            $table->integer('total_safe_day_wlta')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->string('status_request')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents_fix');
    }
};
