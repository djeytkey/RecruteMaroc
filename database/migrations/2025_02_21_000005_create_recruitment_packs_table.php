<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_packs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // essentiel, optimise, strategique
            $table->string('name');
            $table->decimal('price_mad', 10, 2);
            $table->decimal('candidate_reward_mad', 10, 2);
            $table->integer('publication_days');
            $table->json('features')->nullable();
            $table->string('badge_color')->nullable();
            $table->integer('cvtheque_consultations_per_month')->nullable(); // null = illimité for strategique
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_packs');
    }
};
