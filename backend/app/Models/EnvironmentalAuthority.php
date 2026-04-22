<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EnvironmentalAuthority extends Model
{
    protected $fillable = [
        'name',
        'code',
        'department',
    ];

    public function monitorings(): HasMany
    {
        return $this->hasMany(Monitoring::class);
    }

    public function obligations(): HasMany
    {
        return $this->hasMany(Obligation::class);
    }
}
