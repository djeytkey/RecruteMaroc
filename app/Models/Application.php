<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_offer_id', 'user_id', 'compatibility_score', 'status',
        'criteria_scores', 'replied_at', 'rejection_reason',
        'trial_validated_at', 'recruited_at',
    ];

    protected function casts(): array
    {
        return [
            'compatibility_score' => 'decimal:2',
            'criteria_scores' => 'array',
            'replied_at' => 'datetime',
            'trial_validated_at' => 'datetime',
            'recruited_at' => 'datetime',
        ];
    }

    public function jobOffer(): BelongsTo
    {
        return $this->belongsTo(JobOffer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applicationAnswers(): HasMany
    {
        return $this->hasMany(ApplicationAnswer::class);
    }

    public function reward(): HasOne
    {
        return $this->hasOne(Reward::class);
    }
}
