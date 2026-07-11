<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'primary_color',
        'secondary_color',
        'accent_color',
        'font_heading',
        'font_body',
        'layout_config',
        'custom_css',
    ];

    protected function casts(): array
    {
        return [
            'layout_config' => 'array',
        ];
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }
}
