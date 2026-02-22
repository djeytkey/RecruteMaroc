<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sector extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function candidateProfiles(): HasMany
    {
        return $this->hasMany(CandidateProfile::class, 'sector_id');
    }
}
