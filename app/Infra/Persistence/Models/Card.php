<?php

namespace App\Infra\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use SoftDeletes;

    protected $table = 'cards';

    protected $fillable = [
        'uuid',
        'status',
        'last_four_digits',
        'card_brand',
        'monthly_limit_enabled',
        'monthly_limit',
    ];

    protected $casts = [
        'monthly_limit_enabled' => 'boolean',
        'monthly_limit' => 'integer',
        'deleted_at' => 'datetime',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'card_uuid', 'uuid');
    }

    public function status(): BelongsToMany
    {
        return $this->belongsToMany(Status::class, 'description', 'status');
    }
}
