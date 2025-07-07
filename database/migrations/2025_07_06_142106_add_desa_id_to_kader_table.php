<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */ 
    public function up()
    {
        Schema::table('kader', function (Blueprint $table) {
            $table->unsignedBigInteger('desa_id')->nullable()->after('user_id');
            $table->foreign('desa_id')->references('id')->on('desas')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('kader', function (Blueprint $table) {
            $table->dropForeign(['desa_id']);
            $table->dropColumn('desa_id');
        });
    }
};
