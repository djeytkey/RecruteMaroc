<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'recruitment_pack_id', 'sector_id', 'title', 'location', 'contract_type',
        'description', 'main_criteria', 'status', 'stripe_checkout_session_id', 'paid_at',
        'published_at', 'expires_at', 'cvtheque_consultations_used',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function isPaid(): bool
    {
        return $this->paid_at !== null;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function recruitmentPack(): BelongsTo
    {
        return $this->belongsTo(RecruitmentPack::class);
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function offerCriteria(): HasMany
    {
        return $this->hasMany(OfferCriterion::class, 'job_offer_id')->orderBy('order');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_offer_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }
}
