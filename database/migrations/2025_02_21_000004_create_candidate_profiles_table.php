<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('country')->default('Maroc');
            $table->string('city')->nullable();
            $table->string('mobility')->nullable(); // locale, nationale, internationale, teletravail_uniquement
            $table->string('availability')->nullable(); // immediate, 2_semaines, 1_mois, 3_mois, a_definir
            $table->string('experience_range')->nullable(); // 0-1, 1-3, 3-5, 5-10, 10-15, 15+
            $table->string('last_position')->nullable();
            $table->string('job_type_sought')->nullable(); // CDI, CDD, Freelance, Stage, Alternance, Temps partiel
            $table->foreignId('sector_id')->nullable()->constrained()->nullOnDelete();
            $table->string('education_level')->nullable(); // Bac, Bac+2, Bac+3, Bac+5, Doctorat
            $table->string('cv_path')->nullable();
            $table->decimal('completeness_percentage', 5, 2)->default(0);
            $table->json('skills')->nullable(); // [{"name": "...", "level": 25|50|75|100}]
            $table->json('languages')->nullable(); // [{"language": "...", "level": "..."}]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_profiles');
    }
};
