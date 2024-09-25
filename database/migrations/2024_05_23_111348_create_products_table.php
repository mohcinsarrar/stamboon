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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->boolean('fanchart')->nullable()->default(1);
            $table->boolean('pedigree')->nullable()->default(0);
            $table->integer('duration')->nullable();
            $table->integer('print_number')->nullable();
            $table->float('price')->nullable();
            $table->integer('fanchart_max_generation')->nullable();
            $table->integer('pedigree_max_generation')->nullable();
            $table->integer('max_nodes')->nullable();
            $table->string('fanchart_max_output_png')->nullable();
            $table->string('pedigree_max_output_png')->nullable();
            $table->string('fanchart_max_output_pdf')->nullable();
            $table->string('pedigree_max_output_pdf')->nullable();
            $table->boolean('fanchart_output_png')->nullable()->default(0);
            $table->boolean('pedigree_output_png')->nullable()->default(0);
            $table->boolean('fanchart_output_pdf')->nullable()->default(0);
            $table->boolean('pedigree_output_pdf')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
