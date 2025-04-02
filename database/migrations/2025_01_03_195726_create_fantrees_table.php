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
        Schema::create('fantrees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('name');
            $table->string('gedcom_file')->nullable()->default(null);
            $table->string('excel_file')->nullable()->default(null);
            $table->string('status')->default('pending');
            $table->json('stats')->nullable()->default(null);
            $table->integer('print_number')->default(0);
            $table->json('chart_status')->nullable()->default(null);
            $table->string('weapon')->nullable();
            $table->integer('weapon_xpos')->nullable();
            $table->integer('weapon_ypos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fantrees');
    }
};
