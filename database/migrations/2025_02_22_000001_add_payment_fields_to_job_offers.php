<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_offers', function (Blueprint $table) {
            $table->string('stripe_checkout_session_id')->nullable()->after('status');
            $table->timestamp('paid_at')->nullable()->after('stripe_checkout_session_id');
        });
    }

    public function down(): void
    {
        Schema::table('job_offers', function (Blueprint $table) {
            $table->dropColumn(['stripe_checkout_session_id', 'paid_at']);
        });
    }
};
