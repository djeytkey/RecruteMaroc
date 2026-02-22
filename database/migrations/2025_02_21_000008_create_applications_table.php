<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('compatibility_score', 5, 2)->nullable();
            $table->string('status')->default('pending'); // pending, shortlisted, rejected, on_hold, trial_period, trial_validated, recruited, reward_pending, reward_paid
            $table->json('criteria_scores')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('trial_validated_at')->nullable();
            $table->timestamp('recruited_at')->nullable();
            $table->timestamps();
            $table->unique(['job_offer_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
