<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecruitmentPack extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'name', 'price_mad', 'candidate_reward_mad',
        'publication_days', 'features', 'badge_color',
        'cvtheque_consultations_per_month',
    ];

    protected function casts(): array
    {
        return [
            'price_mad' => 'decimal:2',
            'candidate_reward_mad' => 'decimal:2',
            'features' => 'array',
        ];
    }

    public function jobOffers(): HasMany
    {
        return $this->hasMany(JobOffer::class, 'recruitment_pack_id');
    }
}
