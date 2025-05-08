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
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropColumn('keterangan');
            $table->enum('status', ['belum_dimulai', 'berlangsung', 'selesai'])->default('belum_dimulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->text('keterangan')->nullable();
            $table->dropColumn('status');
        });
    }
};
