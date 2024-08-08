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
            $table->string('spouse_link_color')->default('#D3D3D3');
            $table->string('bio_child_link_color')->default('#D3D3D3');
            $table->string('adop_child_link_color')->default('#D3D3D3');
            $table->string('female_color')->default("#FF69B4");
            $table->string('female_text_color')->default("#222423");
            $table->string('male_color')->default("#00CED1");
            $table->string('male_text_color')->default("#222423");
            $table->string('node_template')->default("1");
            $table->string('bg_template')->default("1");
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
