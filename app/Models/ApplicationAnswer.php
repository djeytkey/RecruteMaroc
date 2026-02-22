<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id', 'offer_criterion_id', 'numeric_value',
        'text_value', 'score_contribution',
    ];

    protected function casts(): array
    {
        return ['score_contribution' => 'decimal:2'];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function offerCriterion(): BelongsTo
    {
        return $this->belongsTo(OfferCriterion::class, 'offer_criterion_id');
    }
}
