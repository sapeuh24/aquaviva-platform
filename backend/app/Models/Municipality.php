<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipality extends Model
{
    protected $fillable = [
        'department_id',
        'name',
        'dane_code',
        'dane_department_code',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function monitorings(): HasMany
    {
        return $this->hasMany(Monitoring::class);
    }

    public function organizationProjects(): HasMany
    {
        return $this->hasMany(OrganizationProject::class);
    }
}
