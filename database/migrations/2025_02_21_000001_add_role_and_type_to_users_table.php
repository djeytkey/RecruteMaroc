<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('candidate')->after('email');
            $table->boolean('is_active')->default(true)->after('remember_token');
            $table->string('activation_token')->nullable()->after('is_active');
            $table->timestamp('activated_at')->nullable()->after('activation_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active', 'activation_token', 'activated_at']);
        });
    }
};
