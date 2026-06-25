<?php

namespace App\Infra\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
   use SoftDeletes;

    protected $fillable = [
        'uuid',
        'description',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class, 'status', 'description');
    }
}
