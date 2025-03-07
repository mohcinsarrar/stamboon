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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            // box color settings
            $table->string('box_color')->default("blood");
            $table->string('male_color')->default("#C08219");
            $table->string('female_color')->default("#E0C17E");
            $table->string('blood_color')->default("#C08219");
            $table->string('notblood_color')->default("#E0C17E");

            // text color settings
            $table->string('text_color')->default("#000000");

            // link color settings
            $table->string('spouse_link_color')->default('#000000');
            $table->string('bio_child_link_color')->default('#000000');
            $table->string('adop_child_link_color')->default('#000000');
            
            // portrait band color
            $table->string('band_color')->default("#EFBF04");

            $table->string('node_template')->default("4");
            $table->string('bg_template')->default("0");

            $table->string('default_male_image')->nullable()->default("man1");
            $table->string('default_female_image')->nullable()->default("female1");

            $table->string('note_type')->default('1');
            $table->string('note_text_color')->default('#000000');
            
            $table->string('default_filter')->nullable()->default("none");
            $table->string('photos_type')->default("oval");
            
            $table->string('default_date')->default("YYYY-MM-DD");



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
