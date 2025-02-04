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
        Schema::create('notesfantree', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fantree_id')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->text("content");
            $table->integer("xpos");
            $table->integer("ypos");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notesfantree');
    }
};
