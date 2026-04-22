<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Obligation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'monitoring_id',
        'environmental_authority_id',
        'resolution_number',
        'resolution_date',
        'instrument_type',
        'name',
        'description',
        'legal_basis',
        'environmental_medium',
        'obligation_type',
        'compliance_deadline',
        'compliance_frequency',
        'status',
    ];

    protected $casts = [
        'resolution_date'    => 'date',
        'compliance_deadline' => 'date',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('company', function (Builder $builder): void {
            if (auth()->check()) {
                $builder->where('obligations.company_id', auth()->user()->company_id);
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function monitoring(): BelongsTo
    {
        return $this->belongsTo(Monitoring::class);
    }

    public function environmentalAuthority(): BelongsTo
    {
        return $this->belongsTo(EnvironmentalAuthority::class);
    }

    public function worksheets(): HasMany
    {
        return $this->hasMany(Worksheet::class);
    }

    public function organizationProjects(): BelongsToMany
    {
        return $this->belongsToMany(OrganizationProject::class, 'obligation_organization_project');
    }
}
