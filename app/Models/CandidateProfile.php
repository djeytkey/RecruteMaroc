<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CandidateProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'phone', 'country', 'city',
        'mobility', 'availability', 'experience_range', 'last_position',
        'job_type_sought', 'sector_id', 'education_level', 'cv_path', 'photo_path',
        'completeness_percentage', 'skills', 'languages',
    ];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'languages' => 'array',
            'completeness_percentage' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'user_id', 'user_id');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
