<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Monitoring extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'project_id',
        'municipality_id',
        'environmental_authority_id',
        'name',
        'specification',
        'resolution_number',
        'resolution_date',
    ];

    protected $casts = [
        'resolution_date' => 'date',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function environmentalAuthority(): BelongsTo
    {
        return $this->belongsTo(EnvironmentalAuthority::class);
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function worksheets(): HasMany
    {
        return $this->hasMany(Worksheet::class);
    }

    public function obligations(): HasMany
    {
        return $this->hasMany(Obligation::class);
    }
}
