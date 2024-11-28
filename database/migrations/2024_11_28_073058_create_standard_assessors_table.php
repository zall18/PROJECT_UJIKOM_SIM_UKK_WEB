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
        Schema::create('standard_assessors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competency_standard_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('assessor_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standard_assessors');
    }
};
