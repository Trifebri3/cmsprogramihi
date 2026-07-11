<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Program extends Model
{
    protected $fillable = [
        'theme_id',
        'name',
        'slug',
        'subdomain',
        'custom_domain',
        'logo_path',
        'banner_path',
        'favicon_path',
        'status',
        'contact',
    ];

    protected $casts = [
        'contact' => 'array',
    ];

    /**
     * Get specific contact configuration safely with a fallback.
     */
    public function getContact(string $key, string $default = ''): string
    {
        if (!is_array($this->contact)) {
            return $default;
        }

        $item = $this->contact[strtolower($key)] ?? null;
        if (is_array($item)) {
            return $item['value'] ?? $default;
        }

        return is_string($item) ? $item : $default;
    }

    /**
     * Generate program specific URLs dynamically supporting subdomain and path fallbacks.
     */
    public function url(string $path = ''): string
    {
        $host = request()->getHost();
        $baseDomains = ['localhost', '127.0.0.1', 'domain.com', 'profil.instituthijauindonesia.or.id'];
        $isSubsiteDomain = true;
        foreach ($baseDomains as $baseDomain) {
            if ($host === $baseDomain || $host === 'www.' . $baseDomain) {
                $isSubsiteDomain = false;
            }
        }

        if ($isSubsiteDomain) {
            return url('/' . ltrim($path, '/'));
        }

        return url('/' . $this->slug . '/' . ltrim($path, '/'));
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class)->withDefault([
            'primary_color' => '#2E7D32',
            'secondary_color' => '#FFFFFF',
            'accent_color' => '#FFB300',
            'font_heading' => 'Poppins',
            'font_body' => 'Inter',
            'layout_config' => [
                'navbar' => 'center',
                'footer' => 'modern',
                'hero' => 'fullwidth'
            ]
        ]);
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class)->orderBy('order_no');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class)->orderBy('year_start', 'desc');
    }

    public function activePeriod(): HasOne
    {
        return $this->hasOne(Period::class)->where('status', 'active');
    }

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
