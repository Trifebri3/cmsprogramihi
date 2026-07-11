<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Management extends Model
{
    protected $table = 'managements';

    protected $fillable = [
        'period_id',
        'name',
        'position',
        'photo_path',
        'bio',
        'linkedin',
        'instagram',
        'order_no',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }
}
