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
        // Schema::create('cek_kesehatan', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('lansia_id')->constrained('lansia')->onDelete('cascade');
        //     $table->foreignId('jadwal_id')->constrained('jadwal')->onDelete('cascade');
        //     $table->date('tanggal');
        //     $table->float('berat_badan');
        //     $table->integer('tekanan_darah_sistolik'); // Kolom untuk tekanan sistolik (contoh: 120)
        //     $table->integer('tekanan_darah_diastolik'); // Kolom untuk tekanan diastolik (contoh: 80)
        //     $table->float('gula_darah');
        //     $table->float('kolestrol');
        //     $table->float('asam_urat');
        //     $table->text('diagnosa')->nullable();
        //     $table->timestamps();
        // });

        Schema::create('cek_kesehatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lansia_id')->constrained('lansia')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwal')->onDelete('cascade');
            $table->date('tanggal');
            $table->decimal('berat_badan', 5, 2)->default(0); // Nilai default 0
            $table->integer('tekanan_darah_sistolik')->default(0); // Nilai default 0
            $table->integer('tekanan_darah_diastolik')->default(0); // Nilai default 0
            $table->decimal('gula_darah', 5, 2)->default(0); // Nilai default 0
            $table->decimal('kolestrol', 5, 2)->default(0); // Nilai default 0
            $table->decimal('asam_urat', 4, 2)->default(0); // Nilai default 0
            $table->json('diagnosa')->nullable();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cek_kesehatan');
    }
};
