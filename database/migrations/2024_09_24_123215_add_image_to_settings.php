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
        Schema::table('settings', function (Blueprint $table) {
            //
            $table->string('default_male_image')->nullable()->default("man1.jpg");
            $table->string('default_female_image')->nullable()->default("female1.jpg");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            //
            $table->dropColumn('default_male_image');
            $table->dropColumn('default_female_image');
        });
    }
};
