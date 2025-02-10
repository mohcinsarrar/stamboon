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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->string('payment_id');
            $table->string('currency');
            $table->string('payment_status');
            $table->string('payment_method');
            $table->string('invoice')->nullable();
            $table->boolean('expired')->default(0);
            $table->float('price')->nullable();
            $table->boolean('month')->default(false);
            $table->boolean('week')->default(false);
            $table->boolean('end')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
