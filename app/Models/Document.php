<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'program_id',
        'title',
        'description',
        'file_path',
        'category', // public, internal, archive
        'status', // active, inactive
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
