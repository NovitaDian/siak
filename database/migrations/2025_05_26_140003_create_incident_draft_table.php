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
        Schema::create('incident_draft', function (Blueprint $table) {
            $table->id();
            $table->string('writer', 255);
            $table->date('stamp_date')->nullable();
            $table->date('shift_date');
            $table->string('shift', 255);
            $table->string('safety_officer_1', 255);
            $table->string('status_kejadian', 255)->nullable();
            $table->date('tgl_kejadiannya')->nullable();
            $table->time('jam_kejadiannya')->nullable();
            $table->string('lokasi_kejadiannya', 255)->nullable();
            $table->string('klasifikasi_kejadiannya', 255)->nullable();
            $table->string('ada_korban', 255)->nullable();
            $table->string('ada', 255)->nullable();
            $table->string('nama_korban', 255)->nullable();
            $table->string('status', 255)->nullable();
            $table->string('jenis_kelamin', 50)->nullable();
            $table->string('perusahaan', 255)->nullable();
            $table->string('bagian', 255)->nullable();
            $table->string('jabatan', 255)->nullable();
            $table->integer('masa_kerja')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('jenis_luka_sakit', 255)->nullable();
            $table->string('jenis_luka_sakit2', 255)->nullable();
            $table->string('jenis_luka_sakit3', 255)->nullable();
            $table->string('bagian_tubuh_luka', 255)->nullable();
            $table->string('bagian_tubuh_luka2', 255)->nullable();
            $table->string('bagian_tubuh_luka3', 255)->nullable();
            $table->string('jenis_kejadiannya', 255)->nullable();
            $table->text('penjelasan_kejadiannya')->nullable();
            $table->text('tindakan_pengobatan')->nullable();
            $table->text('tindakan_segera_yang_dilakukan')->nullable();
            $table->text('rincian_dari_pemeriksaan')->nullable();
            $table->string('penyebab_langsung_1_a', 255)->nullable();
            $table->string('penyebab_langsung_1_b', 255)->nullable();
            $table->string('penyebab_langsung_2_a', 255)->nullable();
            $table->string('penyebab_langsung_2_b', 255)->nullable();
            $table->string('penyebab_langsung_3_a', 255)->nullable();
            $table->string('penyebab_langsung_3_b', 255)->nullable();
            $table->string('penyebab_dasar_1_a', 255)->nullable();
            $table->string('penyebab_dasar_1_b', 255)->nullable();
            $table->string('penyebab_dasar_1_c', 255)->nullable();
            $table->string('penyebab_dasar_2_a', 255)->nullable();
            $table->string('penyebab_dasar_2_b', 255)->nullable();
            $table->string('penyebab_dasar_2_c', 255)->nullable();
            $table->string('penyebab_dasar_3_a', 255)->nullable();
            $table->string('penyebab_dasar_3_b', 255)->nullable();
            $table->string('penyebab_dasar_3_c', 255)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_1_a', 255)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_1_b', 255)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_1_c', 255)->nullable();
            $table->text('deskripsi_tindakan_pencegahan_1')->nullable();
            $table->string('pic_1', 255)->nullable();
            $table->string('timing_1', 255)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_2_a', 255)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_2_b', 255)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_2_c', 255)->nullable();
            $table->text('deskripsi_tindakan_pencegahan_2')->nullable();
            $table->string('pic_2', 255)->nullable();
            $table->string('timing_2', 255)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_3_a', 255)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_3_b', 255)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_3_c', 255)->nullable();
            $table->text('deskripsi_tindakan_pencegahan_3')->nullable();
            $table->string('pic_3', 255)->nullable();
            $table->string('timing_3', 255)->nullable();
            $table->integer('jml_employee')->nullable();
            $table->integer('jml_outsources')->nullable();
            $table->integer('jml_security')->nullable();
            $table->integer('jml_loading_stacking')->nullable();
            $table->integer('jml_contractor')->nullable();
            $table->integer('jml_hari_hilang')->nullable();
            $table->string('no_laporan', 255)->nullable();
            $table->integer('lta')->nullable();
            $table->integer('wlta')->nullable();
            $table->integer('trc')->nullable();
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
            $table->integer('urut_kejadiannya')->nullable();
            $table->date('tanggal_urut_kejadiannya')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_draft');
    }
};
