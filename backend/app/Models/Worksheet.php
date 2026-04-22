<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worksheet extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'obligation_id',
        'monitoring_id',
        'monitoring_tool',
        'monitoring_phase',
        'name',
        'objective',
        'target',
        'observations',
    ];

    public function obligation(): BelongsTo
    {
        return $this->belongsTo(Obligation::class);
    }

    public function monitoring(): BelongsTo
    {
        return $this->belongsTo(Monitoring::class);
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class);
    }
}
