<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offer_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_offer_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // quantitative, declarative
            $table->string('label');
            $table->integer('weight_percentage')->default(25); // 0-100, répartis entre les critères
            $table->json('options')->nullable(); // pour déclaratif: ["débutant", "intermédiaire", "bilingue"] ou null si libre
            $table->integer('expected_level')->nullable(); // pour quantitatif: 25, 50, 75, 100
            $table->boolean('is_blocking')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_criteria');
    }
};
