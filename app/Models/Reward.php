<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id', 'amount_mad', 'status', 'iban', 'bank_name', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_mad' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
