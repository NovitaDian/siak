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
            $table->dateTime('stamp_date')->nullable(); // datetime
            $table->date('shift_date');
            $table->string('shift', 8);
            $table->string('status_kejadian', 5)->nullable();
            $table->date('tgl_kejadiannya')->nullable();
            $table->time('jam_kejadiannya')->nullable();
            $table->string('lokasi_kejadiannya', 100)->nullable();
            $table->string('klasifikasi_kejadiannya', 100)->nullable();
            $table->string('ada_korban', 5)->nullable();
            $table->string('ada', 100)->nullable();
            $table->string('nama_korban', 100)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('jenis_kelamin', 100)->nullable();
            $table->unsignedBigInteger('perusahaan_id')->nullable();
            $table->unsignedBigInteger('hse_inspector_id');
            $table->string('bagian', 100)->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->integer('masa_kerja')->nullable(); // sebelumnya salah: integer
            $table->date('tgl_lahir',)->nullable();
            $table->string('jenis_luka_sakit', 255)->nullable();
            $table->string('jenis_luka_sakit2', 255)->nullable();
            $table->string('jenis_luka_sakit3', 255)->nullable();
            $table->string('bagian_tubuh_luka', 255)->nullable();
            $table->string('bagian_tubuh_luka2', 255)->nullable();
            $table->string('bagian_tubuh_luka3', 255)->nullable();
            $table->text('jenis_kejadiannya')->nullable();
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
            $table->string('pic_1', 100)->nullable();
            $table->string('timing_1', 100)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_2_a', 255)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_2_b', 255)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_2_c', 255)->nullable();
            $table->text('deskripsi_tindakan_pencegahan_2')->nullable();
            $table->string('pic_2', 100)->nullable();
            $table->string('timing_2', 100)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_3_a', 255)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_3_b', 255)->nullable();
            $table->string('tindakan_kendali_untuk_peningkatan_3_c', 255)->nullable();
            $table->text('deskripsi_tindakan_pencegahan_3')->nullable();
            $table->string('pic_3', 100)->nullable();
            $table->date('timing_3')->nullable();
            $table->integer('jml_employee')->nullable();
            $table->integer('jml_outsources')->nullable();
            $table->integer('jml_security')->nullable();
            $table->integer('jml_loading_stacking')->nullable();
            $table->integer('jml_contractor')->nullable();
            $table->integer('jml_hari_hilang')->nullable();
            $table->boolean('no_laporan')->nullable();
            $table->boolean('lta')->nullable();
            $table->boolean('wlta')->nullable();
            $table->boolean('trc')->nullable();
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
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('perusahaan_id')->references('id')->on('perusahaan')->onDelete('cascade');
            $table->foreign('hse_inspector_id')->references('id')->on('hse_inspector')->onDelete('cascade');
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
