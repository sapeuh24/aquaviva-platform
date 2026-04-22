<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alert extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'indicator_id',
        'type',
        'title',
        'message',
        'due_date',
        'status',
        'read_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'read_at'  => 'datetime',
    ];

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
