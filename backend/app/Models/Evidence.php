<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evidence extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'evidences';

    protected $fillable = [
        'company_id',
        'activity_id',
        'uploaded_by',
        'original_name',
        'storage_path',
        'mime_type',
        'size_bytes',
        'description',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
