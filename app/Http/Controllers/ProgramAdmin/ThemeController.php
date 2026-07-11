<?php

namespace App\Http\Controllers\ProgramAdmin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    protected function getProgram()
    {
        $user = auth()->user();
        $programId = $user->hasRole('super-admin') ? session('active_program_id') : $user->program_id;
        if (!$programId) {
            abort(403, 'Program context not resolved.');
        }
        return Program::findOrFail($programId);
    }

    public function edit()
    {
        $program = $this->getProgram();
        $theme = $program->theme;

        // Supply a complete Canva-like default layout configuration if it is currently empty or legacy
        if (empty($theme->layout_config) || empty($theme->layout_config['sections'])) {
            $defaultLayout = [
                'navbar' => 'left',
                'footer' => 'modern',
                'design_system' => [
                    'radius' => 'rounded-2xl',
                    'shadow' => 'shadow-md',
                    'border_width' => 'border',
                    'animation' => 'fade-in'
                ],
                'popup_banner' => [
                    'is_active' => false,
                    'type' => 'popup',
                    'trigger_mode' => 'once',
                    'start_date' => '',
                    'end_date' => '',
                    'content_type' => 'image',
                    'media_url' => '',
                    'cta_text' => 'Pelajari Selengkapnya',
                    'cta_url' => ''
                ],
                'sections' => [
                    [
                        'id' => 'sec_hero_' . uniqid(),
                        'type' => 'hero',
                        'is_visible' => true,
                        'padding_y' => 'py-20',
                        'bg_color' => '#1C3F24',
                        'text_color' => '#FFFFFF',
                        'title' => 'Mewujudkan Perubahan Nyata Bersama Kami',
                        'subtitle' => 'Kami percaya pada kekuatan kolaborasi dan inovasi untuk masa depan yang lestari.',
                        'btn_text' => 'Pelajari Program',
                        'btn_url' => '#about',
                        'show_banner' => true
                    ],
                    [
                        'id' => 'sec_about_' . uniqid(),
                        'type' => 'about',
                        'is_visible' => true,
                        'padding_y' => 'py-16',
                        'bg_color' => '#FFFFFF',
                        'text_color' => '#111827',
                        'title' => 'Tentang Kami',
                        'subtitle' => 'Institut Hijau Indonesia adalah wadah kolaborasi sosial dan edukasi lingkungan hidup.',
                        'desc_short' => 'Kami bergerak mendampingi komunitas lokal guna merawat keberlangsungan alam.',
                        'desc_full' => 'Melalui riset, pendampingan lapangan, publikasi modul terbuka, serta akuntabilitas LPJ digital, kami berkomitmen menjaga integritas.',
                        'visi' => 'Menjadi pusat gerakan sosial dan ekologi yang transparan serta kredibel.',
                        'misi' => 'Menyediakan repositori dokumen, tata kelola tim yang sehat, dan pendampingan terpadu.'
                    ],
                    [
                        'id' => 'sec_stats_' . uniqid(),
                        'type' => 'stats',
                        'is_visible' => true,
                        'padding_y' => 'py-16',
                        'bg_color' => '#F9FAFB',
                        'text_color' => '#111827',
                        'title' => 'Pencapaian Kami',
                        'items' => [
                            ['number' => '10+', 'label' => 'Tahun Edukasi'],
                            ['number' => '85%', 'label' => 'Tingkat Kepuasan'],
                            ['number' => '100%', 'label' => 'Transparansi LPJ']
                        ]
                    ],
                    [
                        'id' => 'sec_team_' . uniqid(),
                        'type' => 'team',
                        'is_visible' => true,
                        'padding_y' => 'py-16',
                        'bg_color' => '#FFFFFF',
                        'text_color' => '#111827',
                        'title' => 'Tim Pengurus',
                        'subtitle' => 'Kolaborator handal di balik jalannya program.',
                        'display_style' => 'grid' // grid, carousel
                    ],
                    [
                        'id' => 'sec_gallery_' . uniqid(),
                        'type' => 'gallery',
                        'is_visible' => true,
                        'padding_y' => 'py-16',
                        'bg_color' => '#F9FAFB',
                        'text_color' => '#111827',
                        'title' => 'Galeri Aksi',
                        'subtitle' => 'Dokumentasi visual dari aktivitas lapangan.',
                        'display_style' => 'masonry' // masonry, grid
                    ],
                    [
                        'id' => 'sec_articles_' . uniqid(),
                        'type' => 'articles',
                        'is_visible' => true,
                        'padding_y' => 'py-16',
                        'bg_color' => '#FFFFFF',
                        'text_color' => '#111827',
                        'title' => 'Artikel & Berita',
                        'subtitle' => 'Warta penting dan informasi terbaru dari kami.'
                    ],
                    [
                        'id' => 'sec_documents_' . uniqid(),
                        'type' => 'documents',
                        'is_visible' => true,
                        'padding_y' => 'py-16',
                        'bg_color' => '#F9FAFB',
                        'text_color' => '#111827',
                        'title' => 'Arsip Dokumen',
                        'subtitle' => 'Akses terbuka ke SOP, proposal, dan modul pembelajaran.'
                    ],
                    [
                        'id' => 'sec_contact_' . uniqid(),
                        'type' => 'contact',
                        'is_visible' => true,
                        'padding_y' => 'py-16',
                        'bg_color' => '#FFFFFF',
                        'text_color' => '#111827',
                        'title' => 'Hubungi Kami',
                        'subtitle' => 'Mari berdiskusi dan berkolaborasi.'
                    ]
                ]
            ];
            $theme->update(['layout_config' => $defaultLayout]);
            $theme = $theme->fresh();
        }

        return view('program.theme.edit', compact('program', 'theme'));
    }

    public function update(Request $request)
    {
        $program = $this->getProgram();
        $theme = $program->theme;

        $request->validate([
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'accent_color' => 'required|string|max:7',
            'font_heading' => 'required|string|max:255',
            'font_body' => 'required|string|max:255',
            'custom_css' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,ico,svg|max:1024',
            'popup_media' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072',
            'layout_config_json' => 'required|string',
            'menus_json' => 'nullable|string',
        ]);

        // 1. Process standard brand file uploads (Logo, Banner, Favicon)
        $programData = [];

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $programData['logo_path'] = $logoPath;
        }

        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('banners', 'public');
            $programData['banner_path'] = $bannerPath;
        }

        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('favicons', 'public');
            $programData['favicon_path'] = $faviconPath;
        }

        if (!empty($programData)) {
            $program->update($programData);
        }

        // 2. Process layout JSON schema & optional popup media
        $layoutConfig = json_decode($request->input('layout_config_json'), true);
        if (!is_array($layoutConfig)) {
            $layoutConfig = $theme->layout_config ?? [];
        }

        if ($request->hasFile('popup_media')) {
            $mediaPath = $request->file('popup_media')->store('popups', 'public');
            if (!isset($layoutConfig['popup_banner'])) {
                $layoutConfig['popup_banner'] = [];
            }
            $layoutConfig['popup_banner']['media_url'] = asset('storage/' . $mediaPath);
        }

        // 3. Rebuild Menus dynamically
        if ($request->filled('menus_json')) {
            $menusData = json_decode($request->input('menus_json'), true);
            if (is_array($menusData)) {
                $program->menus()->delete();
                foreach ($menusData as $idx => $m) {
                    $parentMenu = $program->menus()->create([
                        'name' => $m['name'],
                        'slug' => \Illuminate\Support\Str::slug($m['name']),
                        'url' => $m['url'] ?? '#',
                        'order_no' => $idx + 1,
                        'status' => 'active',
                    ]);
                    if (!empty($m['children']) && is_array($m['children'])) {
                        foreach ($m['children'] as $childIdx => $child) {
                            $program->menus()->create([
                                'parent_id' => $parentMenu->id,
                                'name' => $child['name'],
                                'slug' => \Illuminate\Support\Str::slug($child['name']),
                                'url' => $child['url'] ?? '#',
                                'order_no' => $childIdx + 1,
                                'status' => 'active',
                            ]);
                        }
                    }
                }
            }
        }

        // 4. Save Theme Data
        $theme->update([
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'accent_color' => $request->accent_color,
            'font_heading' => $request->font_heading,
            'font_body' => $request->font_body,
            'layout_config' => $layoutConfig,
            'custom_css' => $request->custom_css,
        ]);

        return redirect()->route('cms.theme.edit')->with('status', 'Theme builder customizations saved and applied successfully.');
    }
}
