<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfferCriterion extends Model
{
    use HasFactory;

    protected $table = 'offer_criteria';

    protected $fillable = [
        'job_offer_id', 'type', 'label', 'weight_percentage',
        'options', 'expected_level', 'is_blocking', 'order',
    ];

    protected function casts(): array
    {
        return ['options' => 'array'];
    }

    public function jobOffer(): BelongsTo
    {
        return $this->belongsTo(JobOffer::class);
    }

    public function applicationAnswers(): HasMany
    {
        return $this->hasMany(ApplicationAnswer::class, 'offer_criterion_id');
    }
}
