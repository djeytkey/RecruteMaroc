<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'city', 'country',
        'description', 'website', 'logo', 'is_activated',
    ];

    protected function casts(): array
    {
        return ['is_activated' => 'boolean'];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'company_id');
    }

    public function jobOffers(): HasMany
    {
        return $this->hasMany(JobOffer::class, 'company_id');
    }
}
