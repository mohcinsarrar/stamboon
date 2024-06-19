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
        Schema::create('nodes', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('sex')->nullable();
            $table->string('birth')->nullable();
            $table->string('death')->nullable();
            $table->string('image')->nullable();
            $table->integer('pid')->nullable();
            $table->boolean('root')->default(false);
            $table->boolean('empty')->default(true);
            # change position to unsignedinteget with index and unique
            $table->string('position');
            $table->integer('generation');
            $table->foreignId('tree_id')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nodes');
    }
};
