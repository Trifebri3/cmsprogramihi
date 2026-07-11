<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    protected $fillable = [
        'program_id',
        'name',
        'year_start',
        'year_end',
        'status', // active, archived
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function managements(): HasMany
    {
        return $this->hasMany(Management::class)->orderBy('order_no');
    }
}
