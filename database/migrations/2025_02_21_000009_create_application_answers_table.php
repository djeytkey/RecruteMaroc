<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('offer_criterion_id')->constrained('offer_criteria')->cascadeOnDelete();
            $table->integer('numeric_value')->nullable(); // 25, 50, 75, 100 for quantitative
            $table->text('text_value')->nullable(); // for declarative
            $table->decimal('score_contribution', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['application_id', 'offer_criterion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_answers');
    }
};
