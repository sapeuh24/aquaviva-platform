<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndicatorControl extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'indicator_id',
        'user_id',
        'compliance_percentage',
        'notes',
        'recorded_at',
    ];

    protected $casts = [
        'compliance_percentage' => 'decimal:2',
        'recorded_at'           => 'datetime',
    ];

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
