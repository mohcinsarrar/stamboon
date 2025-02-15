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
        Schema::table('pedigrees', function (Blueprint $table) {
            $table->integer('weapon_xpos')->nullable();
            $table->integer('weapon_ypos')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedigrees', function (Blueprint $table) {
            $table->dropColumn('weapon_xpos');
            $table->dropColumn('weapon_ypos');

        });
    }
};
