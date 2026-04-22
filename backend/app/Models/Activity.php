<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'indicator_id',
        'name',
        'description',
        'scheduled_date',
        'executed_date',
        'compliance_status',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'executed_date'  => 'date',
    ];

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'activity_user');
    }
}
