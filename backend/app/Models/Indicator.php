<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indicator extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'worksheet_id',
        'indicator_frequency_id',
        'name',
        'objective',
        'target',
        'measurement_unit',
        'baseline_value',
        'target_value',
        'next_due_date',
        'is_active',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'next_due_date'  => 'date',
        'baseline_value' => 'decimal:4',
        'target_value'   => 'decimal:4',
    ];

    public function worksheet(): BelongsTo
    {
        return $this->belongsTo(Worksheet::class);
    }

    public function indicatorFrequency(): BelongsTo
    {
        return $this->belongsTo(IndicatorFrequency::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    public function indicatorControls(): HasMany
    {
        return $this->hasMany(IndicatorControl::class);
    }
}
