@extends('layouts.program-admin.app')

@section('header')
<div class="flex justify-between items-center w-full">
    <h2 class="font-extrabold text-xl text-gray-900 leading-tight">
        ✨ {{ __('Dynamic Canvas Page Builder') }} - <span class="text-indigo-600">{{ $program->name }}</span>
    </h2>
    <span class="text-xs font-bold text-slate-400 bg-slate-100 px-3.5 py-1.5 rounded-full uppercase tracking-wider">
        Canva-Like Experience
    </span>
</div>
@endsection

@section('content')
<div class="py-6 px-4 max-w-[1600px] mx-auto">
    @if (session('status'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-semibold shadow-sm flex items-center gap-2">
            <span>✅</span> {{ session('status') }}
        </div>
    @endif

    <form id="builder-form" action="{{ route('cms.theme.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        @csrf
        @method('PATCH')

        <!-- Hidden input payloads -->
        <input type="hidden" name="layout_config_json" id="layout_config_json_input">
        <input type="hidden" name="menus_json" id="menus_json_input">

        <!-- ==================== LEFT: BUILDER CONTROLLER (5 columns) ==================== -->
        <div class="lg:col-span-5 space-y-6" x-data="{ currentTab: 'branding' }">
            <!-- Navigation Builder Tabs -->
            <div class="bg-white p-2 rounded-2xl border border-gray-150 shadow-sm flex gap-1.5 overflow-x-auto">
                <button type="button" @click="currentTab = 'branding'" :class="currentTab === 'branding' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50'" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 uppercase tracking-wider">
                    🎨 Style & Brand
                </button>
                <button type="button" @click="currentTab = 'navbar'" :class="currentTab === 'navbar' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50'" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 uppercase tracking-wider">
                    🌐 Nav Menu
                </button>
                <button type="button" @click="currentTab = 'sections'" :class="currentTab === 'sections' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50'" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 uppercase tracking-wider">
                    🥞 Layout Sections
                </button>
                <button type="button" @click="currentTab = 'popups'" :class="currentTab === 'popups' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50'" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 uppercase tracking-wider">
                    📢 Promo Popups
                </button>
            </div>

            <!-- TAB 1: BRANDING & DESIGN SYSTEM -->
            <div x-show="currentTab === 'branding'" class="bg-white p-8 rounded-3xl border border-gray-150 shadow-sm space-y-8">
                <div>
                    <h3 class="font-extrabold text-lg text-slate-900 uppercase">Design System & Branding</h3>
                    <p class="text-xs text-slate-400 mt-1">Ubah skema warna, Google Fonts, radius sudut, dan logo program Anda.</p>
                </div>

                <!-- Colors -->
                <div class="space-y-4 pt-4 border-t border-slate-50">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Skema Warna (Palette)</h4>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-center text-center">
                            <label class="text-[10px] font-bold text-slate-500 mb-2 uppercase">Utama</label>
                            <input type="color" name="primary_color" id="primary_color" value="{{ $theme->primary_color }}" class="w-12 h-12 rounded-xl cursor-pointer border-0 p-1 bg-white shadow-sm" oninput="liveUpdateColors()">
                        </div>
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-center text-center">
                            <label class="text-[10px] font-bold text-slate-500 mb-2 uppercase">Latar</label>
                            <input type="color" name="secondary_color" id="secondary_color" value="{{ $theme->secondary_color }}" class="w-12 h-12 rounded-xl cursor-pointer border-0 p-1 bg-white shadow-sm" oninput="liveUpdateColors()">
                        </div>
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-center text-center">
                            <label class="text-[10px] font-bold text-slate-500 mb-2 uppercase">Aksen</label>
                            <input type="color" name="accent_color" id="accent_color" value="{{ $theme->accent_color }}" class="w-12 h-12 rounded-xl cursor-pointer border-0 p-1 bg-white shadow-sm" oninput="liveUpdateColors()">
                        </div>
                    </div>
                </div>

                <!-- Fonts -->
                <div class="space-y-4 pt-4 border-t border-slate-50">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Tipografi (Google Fonts)</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Font Judul</label>
                            <select name="font_heading" id="font_heading" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white">
                                <option value="Poppins" {{ $theme->font_heading === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                <option value="Playfair Display" {{ $theme->font_heading === 'Playfair Display' ? 'selected' : '' }}>Playfair Display</option>
                                <option value="Montserrat" {{ $theme->font_heading === 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                                <option value="Outfit" {{ $theme->font_heading === 'Outfit' ? 'selected' : '' }}>Outfit</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Font Isi</label>
                            <select name="font_body" id="font_body" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white">
                                <option value="Inter" {{ $theme->font_body === 'Inter' ? 'selected' : '' }}>Inter</option>
                                <option value="Roboto" {{ $theme->font_body === 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                <option value="Open Sans" {{ $theme->font_body === 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                <option value="Poppins" {{ $theme->font_body === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Radius, Shadow & Borders -->
                <div class="space-y-4 pt-4 border-t border-slate-50">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Style & Visual Box</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Radius Sudut (Rounded)</label>
                            <select id="ds_radius" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white" onchange="markDirty()">
                                <option value="rounded-none">Siku-Siku (Flat)</option>
                                <option value="rounded-xl">Sedang (Rounded XL)</option>
                                <option value="rounded-2xl">Besar (Rounded 2XL)</option>
                                <option value="rounded-[32px]">Melengkung (Canva-Style)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Ketebalan Border</label>
                            <select id="ds_border" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white" onchange="markDirty()">
                                <option value="border-0">Tanpa Border</option>
                                <option value="border">Tipis (1px)</option>
                                <option value="border-2">Tebal (2px)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Branding Media Files -->
                <div class="space-y-4 pt-4 border-t border-slate-50">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Berkas Gambar Branding</h4>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Logo Program (.png/.svg)</label>
                            <input type="file" name="logo" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Banner Utama / Cover</label>
                            <input type="file" name="banner" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Favicon (.ico/.png)</label>
                            <input type="file" name="favicon" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>
                </div>

                <!-- Advanced Custom CSS -->
                <div class="space-y-4 pt-4 border-t border-slate-50">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Custom CSS Style</h4>
                    <textarea name="custom_css" rows="3" class="w-full rounded-xl border-slate-200 text-xs font-mono bg-slate-50" placeholder="/* custom CSS overrides */">{{ $theme->custom_css }}</textarea>
                </div>
            </div>

            <!-- TAB 2: DYNAMIC NAVBAR BUILDER -->
            <div x-show="currentTab === 'navbar'" class="bg-white p-8 rounded-3xl border border-gray-150 shadow-sm space-y-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-extrabold text-lg text-slate-900 uppercase">Navbar Builder</h3>
                        <p class="text-xs text-slate-400 mt-1">Susun link menu, atur urutan, dan kelola dropdown navigasi.</p>
                    </div>
                    <button type="button" onclick="addNewMenu()" class="px-3.5 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-xl text-xs font-bold transition">
                        + Tambah Menu
                    </button>
                </div>

                <!-- Navigation List -->
                <div id="menus-container" class="space-y-4 max-h-[500px] overflow-y-auto pr-1">
                    <!-- Javascript will render menu items dynamically -->
                </div>
            </div>

            <!-- TAB 3: DYNAMIC SECTIONS LIST -->
            <div x-show="currentTab === 'sections'" class="bg-white p-8 rounded-3xl border border-gray-150 shadow-sm space-y-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-extrabold text-lg text-slate-900 uppercase">Section Builder</h3>
                        <p class="text-xs text-slate-400 mt-1">Atur urutan tampilan halaman landing program secara visual.</p>
                    </div>
                    <div class="relative inline-block text-left" x-data="{ open: false }">
                        <button type="button" @click="open = !open" class="px-3.5 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl text-xs font-bold transition">
                            + Tambah Section
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-2xl shadow-xl bg-white border border-gray-100 p-2 z-50 space-y-1 text-left text-xs font-semibold" style="display: none;">
                            <button type="button" onclick="addNewSection('stats')" @click="open = false" class="w-full text-left px-3 py-2 hover:bg-slate-50 rounded-xl block">📈 Angka / Statistik</button>
                            <button type="button" onclick="addNewSection('faq')" @click="open = false" class="w-full text-left px-3 py-2 hover:bg-slate-50 rounded-xl block">❓ FAQ / Accordion</button>
                            <button type="button" onclick="addNewSection('cta')" @click="open = false" class="w-full text-left px-3 py-2 hover:bg-slate-50 rounded-xl block">📢 CTA / Tombol Ajakan</button>
                            <button type="button" onclick="addNewSection('testimonials')" @click="open = false" class="w-full text-left px-3 py-2 hover:bg-slate-50 rounded-xl block">💬 Testimoni Relawan</button>
                            <button type="button" onclick="addNewSection('sponsors')" @click="open = false" class="w-full text-left px-3 py-2 hover:bg-slate-50 rounded-xl block">🤝 Sponsor & Mitra</button>
                        </div>
                    </div>
                </div>

                <!-- Sections List UI -->
                <div id="sections-container" class="space-y-4">
                    <!-- Javascript will render active layout sections here -->
                </div>
            </div>

            <!-- TAB 4: POPUPS / ADVERTISING -->
            <div x-show="currentTab === 'popups'" class="bg-white p-8 rounded-3xl border border-gray-150 shadow-sm space-y-6">
                <div>
                    <h3 class="font-extrabold text-lg text-slate-900 uppercase">Splash Screen / Popup Banner</h3>
                    <p class="text-xs text-slate-400 mt-1">Kelola banner pengumuman penting yang langsung muncul ketika publik berkunjung.</p>
                </div>

                <div class="space-y-4 pt-4 border-t border-slate-50">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-slate-700 uppercase">Status Aktif Banner</label>
                        <select id="pop_active" class="rounded-xl border-slate-200 text-xs font-bold bg-white" onchange="markDirty()">
                            <option value="true">Aktif (Tampilkan)</option>
                            <option value="false">Nonaktif (Sembunyikan)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Jenis Tampilan</label>
                        <select id="pop_type" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white" onchange="markDirty()">
                            <option value="popup">Center Modal Popup</option>
                            <option value="splash">Full Screen Splash Screen</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Aturan Tampil</label>
                        <select id="pop_trigger" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white" onchange="markDirty()">
                            <option value="once">Sekali saja per kunjungan</option>
                            <option value="always">Setiap kali halaman dibuka</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Tanggal Mulai</label>
                            <input type="date" id="pop_start" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white" onchange="markDirty()">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Tanggal Selesai</label>
                            <input type="date" id="pop_end" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white" onchange="markDirty()">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Upload File Media (.jpg/.png/.gif)</label>
                        <input type="file" name="popup_media" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="text-[10px] text-slate-400 mt-1">Gunakan gambar rasio 4:3 atau banner flyer.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Label Tombol CTA</label>
                            <input type="text" id="pop_cta_text" class="w-full rounded-xl border-slate-200 text-xs bg-white" placeholder="Contoh: Daftar Sekarang" oninput="markDirty()">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Link URL CTA</label>
                            <input type="text" id="pop_cta_url" class="w-full rounded-xl border-slate-200 text-xs bg-white" placeholder="https://..." oninput="markDirty()">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action buttons sticky footer -->
            <div class="p-4 bg-slate-900 rounded-3xl shadow-xl flex justify-between items-center text-white">
                <div class="text-xs">
                    <span class="font-extrabold text-indigo-400 uppercase tracking-widest block">Status Builder</span>
                    <span id="save-status" class="text-slate-400 font-medium">Ready</span>
                </div>
                <button type="submit" onclick="prepareSubmit()" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-md transition-transform active:scale-95 uppercase tracking-wider">
                    Simpan Layout & Terapkan
                </button>
            </div>
        </div>

        <!-- ==================== RIGHT: REAL-TIME PREVIEW PANEL (7 columns) ==================== -->
        <div class="lg:col-span-7 bg-white rounded-3xl border border-gray-150 shadow-sm overflow-hidden flex flex-col h-[calc(100vh-140px)] sticky top-6">
            <div class="p-4 bg-slate-50 border-b border-gray-150 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-400"></span>
                    <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                    <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                    <span class="text-xs font-bold text-slate-400 ml-4 font-mono select-none">http://localhost/p/{{ $program->slug }}</span>
                </div>
                <button type="button" onclick="refreshIframe()" class="p-2 hover:bg-slate-200 rounded-xl transition text-slate-500" title="Refresh Live View">
                    🔄 Refresh Preview
                </button>
            </div>
            <!-- Interactive Iframe -->
            <div class="flex-grow bg-slate-100 relative">
                <iframe 
                    id="preview-iframe"
                    src="{{ $program->url('/') }}" 
                    class="w-full h-full border-0 transition-opacity duration-300"
                    onload="onIframeLoaded()"
                ></iframe>
            </div>
        </div>

    </form>
</div>

<!-- Drawer / Modal container for editing detailed section components -->
<div id="section-drawer" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex justify-end">
    <div class="bg-white w-full max-w-lg shadow-2xl border-l border-slate-150 flex flex-col h-full transform translate-x-full transition-transform duration-300 overflow-y-auto p-8 space-y-6">
        <!-- Drawer Header -->
        <div class="flex justify-between items-center pb-4 border-b border-slate-100">
            <div>
                <h3 class="font-black text-lg text-slate-900 uppercase" id="drawer-title">Edit Section</h3>
                <p class="text-xs text-slate-400 mt-0.5">Sesuaikan teks, gambar, layout, dan padding section ini.</p>
            </div>
            <button type="button" onclick="closeDrawer()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition">
                ✕
            </button>
        </div>
        <!-- Drawer Form Area -->
        <div id="drawer-form-content" class="space-y-6">
            <!-- Dynamically populated fields -->
        </div>
    </div>
</div>

<script>
    // Load initial JSON data passed from Controller
    let layoutConfig = @json($theme->layout_config);
    let menus = @json($program->menus()->whereNull('parent_id')->with('children')->get());

    document.addEventListener('DOMContentLoaded', () => {
        // Auto initialize default menus if none exist
        if (menus.length === 0) {
            menus = [
                { name: 'Beranda', url: '/', children: [] },
                { name: 'Kabar Terbaru', url: '/news', children: [] },
                { name: 'Pengurus', url: '/management', children: [] },
                { name: 'Galeri', url: '/gallery', children: [] },
                { name: 'Dokumen', url: '/documents', children: [] }
            ];
            markDirty();
        }

        // Initialize fields inside layout configuration
        if (!layoutConfig.design_system) {
            layoutConfig.design_system = { radius: 'rounded-2xl', shadow: 'shadow-md', border_width: 'border', animation: 'fade-in' };
        }
        if (!layoutConfig.popup_banner) {
            layoutConfig.popup_banner = { is_active: false, type: 'popup', trigger_mode: 'once', start_date: '', end_date: '', media_url: '', cta_text: '', cta_url: '' };
        }

        // Hydrate inputs
        document.getElementById('ds_radius').value = layoutConfig.design_system.radius || 'rounded-2xl';
        document.getElementById('ds_border').value = layoutConfig.design_system.border_width || 'border';
        
        const pb = layoutConfig.popup_banner;
        document.getElementById('pop_active').value = String(pb.is_active);
        document.getElementById('pop_type').value = pb.type || 'popup';
        document.getElementById('pop_trigger').value = pb.trigger_mode || 'once';
        document.getElementById('pop_start').value = pb.start_date || '';
        document.getElementById('pop_end').value = pb.end_date || '';
        document.getElementById('pop_cta_text').value = pb.cta_text || '';
        document.getElementById('pop_cta_url').value = pb.cta_url || '';

        // Render visual menus & layout sections list
        renderMenus();
        renderSections();
    });

    function markDirty() {
        document.getElementById('save-status').innerText = 'Unsaved changes';
        document.getElementById('save-status').classList.remove('text-slate-400');
        document.getElementById('save-status').classList.add('text-amber-400');
    }

    // ==================== NAVBAR BUILDER ====================
    function renderMenus() {
        const container = document.getElementById('menus-container');
        container.innerHTML = '';

        if (menus.length === 0) {
            container.innerHTML = `<p class="text-slate-400 text-xs italic text-center py-6">Belum ada link menu navigasi. Tambahkan di atas.</p>`;
            return;
        }

        menus.forEach((menu, index) => {
            const childrenHtml = (menu.children || []).map((child, childIdx) => `
                <div class="pl-6 flex items-center gap-3 bg-slate-50/50 p-2 rounded-xl border border-slate-100">
                    <span class="text-slate-400 text-xs font-bold">└</span>
                    <input type="text" value="${child.name}" oninput="updateChildMenu(${index}, ${childIdx}, 'name', this.value)" class="rounded-xl border-slate-200 text-xs font-semibold py-1 px-2.5 bg-white flex-grow" placeholder="Label Submenu">
                    <input type="text" value="${child.url}" oninput="updateChildMenu(${index}, ${childIdx}, 'url', this.value)" class="rounded-xl border-slate-200 text-xs py-1 px-2.5 bg-white max-w-[150px]" placeholder="Link Submenu">
                    <button type="button" onclick="deleteChildMenu(${index}, ${childIdx})" class="text-red-500 hover:text-red-700 text-xs font-bold px-1">✕</button>
                </div>
            `).join('');

            container.innerHTML += `
                <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-150 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-black select-none">${index + 1}</span>
                        <input type="text" value="${menu.name}" oninput="updateParentMenu(${index}, 'name', this.value)" class="rounded-xl border-slate-200 text-xs font-black py-1.5 px-3 bg-white flex-grow" placeholder="Label Menu">
                        <input type="text" value="${menu.url}" oninput="updateParentMenu(${index}, 'url', this.value)" class="rounded-xl border-slate-200 text-xs py-1.5 px-3 bg-white max-w-[160px]" placeholder="URL link">
                        
                        <div class="flex items-center gap-1">
                            <button type="button" onclick="moveMenu(${index}, -1)" class="p-1 hover:bg-slate-200 rounded-lg text-xs" title="Pindahkan Ke Atas">▲</button>
                            <button type="button" onclick="moveMenu(${index}, 1)" class="p-1 hover:bg-slate-200 rounded-lg text-xs" title="Pindahkan Ke Bawah">▼</button>
                            <button type="button" onclick="addChildMenu(${index})" class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-bold" title="Tambah Submenu">Dropdown +</button>
                            <button type="button" onclick="deleteParentMenu(${index})" class="p-1.5 text-red-500 hover:text-red-700 text-xs font-bold">✕</button>
                        </div>
                    </div>
                    ${childrenHtml ? `<div class="space-y-2 mt-2">${childrenHtml}</div>` : ''}
                </div>
            `;
        });
    }

    function addNewMenu() {
        menus.push({ name: 'Menu Baru', url: '#', children: [] });
        markDirty();
        renderMenus();
    }

    function updateParentMenu(index, key, val) {
        menus[index][key] = val;
        markDirty();
    }

    function deleteParentMenu(index) {
        menus.splice(index, 1);
        markDirty();
        renderMenus();
    }

    function moveMenu(index, direction) {
        const target = index + direction;
        if (target < 0 || target >= menus.length) return;
        const temp = menus[index];
        menus[index] = menus[target];
        menus[target] = temp;
        markDirty();
        renderMenus();
    }

    function addChildMenu(index) {
        if (!menus[index].children) menus[index].children = [];
        menus[index].children.push({ name: 'Submenu', url: '#' });
        markDirty();
        renderMenus();
    }

    function updateChildMenu(parentIdx, childIdx, key, val) {
        menus[parentIdx].children[childIdx][key] = val;
        markDirty();
    }

    function deleteChildMenu(parentIdx, childIdx) {
        menus[parentIdx].children.splice(childIdx, 1);
        markDirty();
        renderMenus();
    }

    // ==================== SECTIONS BUILDER ====================
    function renderSections() {
        const container = document.getElementById('sections-container');
        container.innerHTML = '';

        if (!layoutConfig.sections || layoutConfig.sections.length === 0) {
            container.innerHTML = `<p class="text-slate-400 text-xs italic text-center py-6">Belum ada section terdaftar.</p>`;
            return;
        }

        layoutConfig.sections.forEach((sec, index) => {
            const visibilityIcon = sec.is_visible ? '👁️' : '👁️‍🗨️';
            const visibilityTitle = sec.is_visible ? 'Sembunyikan' : 'Tampilkan';
            
            container.innerHTML += `
                <div class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100/70 border border-slate-150 rounded-2xl transition group">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-black select-none">${index + 1}</span>
                        <div>
                            <span class="text-xs font-bold text-slate-900 block capitalize">${sec.type} Section</span>
                            <span class="text-[10px] text-slate-400 font-semibold truncate max-w-[200px] block">${sec.title || sec.type}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1">
                        <button type="button" onclick="moveSection(${index}, -1)" class="p-1 hover:bg-slate-250 rounded-lg text-xs" title="Move Up">▲</button>
                        <button type="button" onclick="moveSection(${index}, 1)" class="p-1 hover:bg-slate-250 rounded-lg text-xs" title="Move Down">▼</button>
                        <button type="button" onclick="toggleSectionVisibility(${index})" class="p-1 hover:bg-slate-250 rounded-lg text-xs" title="${visibilityTitle}">${visibilityIcon}</button>
                        <button type="button" onclick="openSectionDrawer(${index})" class="px-2.5 py-1 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-lg text-[10px] font-bold" title="Edit Content">Edit properties</button>
                        <button type="button" onclick="duplicateSection(${index})" class="p-1 hover:bg-slate-250 rounded-lg text-xs" title="Duplicate">📋</button>
                        <button type="button" onclick="deleteSection(${index})" class="p-1 hover:bg-slate-250 rounded-lg text-red-500 hover:text-red-700 text-xs font-bold" title="Delete">✕</button>
                    </div>
                </div>
            `;
        });
    }

    function addNewSection(type) {
        if (!layoutConfig.sections) layoutConfig.sections = [];
        const newSec = {
            id: 'sec_' + type + '_' + Date.now(),
            type: type,
            is_visible: true,
            padding_y: 'py-16',
            bg_color: '#FFFFFF',
            text_color: '#111827',
            title: 'Section Title',
            subtitle: 'Section Description'
        };

        if (type === 'stats') {
            newSec.items = [
                { number: '100+', label: 'Anggota Aktif' },
                { number: '50+', label: 'Aksi Lapangan' }
            ];
        } else if (type === 'faq') {
            newSec.faqs = [
                { q: 'Pertanyaan Pertama?', a: 'Ini adalah jawaban penjelasan pertama.' }
            ];
        } else if (type === 'cta') {
            newSec.btn_text = 'Daftar Sekarang';
            newSec.btn_url = '#';
        } else if (type === 'testimonials') {
            newSec.items = [
                { quote: 'Program ini membawa banyak kebaikan bagi lingkungan.', author: 'Relawan', role: 'Anggota' }
            ];
        } else if (type === 'sponsors') {
            newSec.logos = ['Mitra A', 'Mitra B', 'Kolaborator C'];
        }

        layoutConfig.sections.push(newSec);
        markDirty();
        renderSections();
    }

    function duplicateSection(index) {
        const clone = JSON.parse(JSON.stringify(layoutConfig.sections[index]));
        clone.id = 'sec_' + clone.type + '_' + Date.now();
        clone.title += ' (Salinan)';
        layoutConfig.sections.splice(index + 1, 0, clone);
        markDirty();
        renderSections();
    }

    function deleteSection(index) {
        layoutConfig.sections.splice(index, 1);
        markDirty();
        renderSections();
    }

    function toggleSectionVisibility(index) {
        layoutConfig.sections[index].is_visible = !layoutConfig.sections[index].is_visible;
        markDirty();
        renderSections();
    }

    function moveSection(index, direction) {
        const target = index + direction;
        if (target < 0 || target >= layoutConfig.sections.length) return;
        const temp = layoutConfig.sections[index];
        layoutConfig.sections[index] = layoutConfig.sections[target];
        layoutConfig.sections[target] = temp;
        markDirty();
        renderSections();
    }

    // ==================== SECTION EDITOR DRAWER ====================
    let editingSectionIndex = null;

    function openSectionDrawer(index) {
        editingSectionIndex = index;
        const sec = layoutConfig.sections[index];
        
        document.getElementById('drawer-title').innerText = `Edit: ${sec.type.toUpperCase()} Component`;
        const contentArea = document.getElementById('drawer-form-content');
        contentArea.innerHTML = '';

        // Standard fields for all sections (background, padding, margins)
        let formHtml = `
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Warna Latar (Hex)</label>
                    <input type="color" value="${sec.bg_color || '#FFFFFF'}" oninput="updateEditingSection('bg_color', this.value)" class="w-full h-10 rounded-xl cursor-pointer border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Warna Teks (Hex)</label>
                    <input type="color" value="${sec.text_color || '#111827'}" oninput="updateEditingSection('text_color', this.value)" class="w-full h-10 rounded-xl cursor-pointer border-slate-200">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Lebar Jarak (Padding Y)</label>
                <select onchange="updateEditingSection('padding_y', this.value)" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white">
                    <option value="py-8" ${sec.padding_y === 'py-8' ? 'selected' : ''}>Kecil (py-8)</option>
                    <option value="py-16" ${sec.padding_y === 'py-16' ? 'selected' : ''}>Sedang (py-16)</option>
                    <option value="py-24" ${sec.padding_y === 'py-24' ? 'selected' : ''}>Lebar (py-24)</option>
                    <option value="py-32" ${sec.padding_y === 'py-32' ? 'selected' : ''}>Sangat Lebar (py-32)</option>
                </select>
            </div>
            <div class="border-t border-slate-100 pt-4 space-y-4">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Teks & Info Utama</h4>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Judul Utama</label>
                    <input type="text" value="${sec.title || ''}" oninput="updateEditingSection('title', this.value)" class="w-full rounded-xl border-slate-200 text-xs bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Sub Judul / Deskripsi</label>
                    <textarea rows="3" oninput="updateEditingSection('subtitle', this.value)" class="w-full rounded-xl border-slate-200 text-xs bg-white">${sec.subtitle || ''}</textarea>
                </div>
            </div>
        `;

        // Type-specific field configurations
        if (sec.type === 'hero') {
            formHtml += `
                <div class="border-t border-slate-100 pt-4 space-y-4">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Hero Button Action</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Teks Tombol</label>
                            <input type="text" value="${sec.btn_text || ''}" oninput="updateEditingSection('btn_text', this.value)" class="w-full rounded-xl border-slate-200 text-xs bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Link URL Tombol</label>
                            <input type="text" value="${sec.btn_url || ''}" oninput="updateEditingSection('btn_url', this.value)" class="w-full rounded-xl border-slate-200 text-xs bg-white">
                        </div>
                    </div>
                </div>
            `;
        } else if (sec.type === 'about') {
            formHtml += `
                <div class="border-t border-slate-100 pt-4 space-y-4">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Profil Detail & Visi Misi</h4>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Ringkasan Pendek</label>
                        <textarea rows="2" oninput="updateEditingSection('desc_short', this.value)" class="w-full rounded-xl border-slate-200 text-xs bg-white">${sec.desc_short || ''}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Penjelasan Lengkap</label>
                        <textarea rows="3" oninput="updateEditingSection('desc_full', this.value)" class="w-full rounded-xl border-slate-200 text-xs bg-white">${sec.desc_full || ''}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Visi Program</label>
                        <input type="text" value="${sec.visi || ''}" oninput="updateEditingSection('visi', this.value)" class="w-full rounded-xl border-slate-200 text-xs bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Misi Program (Pisahkan dengan baris baru)</label>
                        <textarea rows="3" oninput="updateEditingSection('misi', this.value)" class="w-full rounded-xl border-slate-200 text-xs bg-white">${sec.misi || ''}</textarea>
                    </div>
                </div>
            `;
        } else if (sec.type === 'cta') {
            formHtml += `
                <div class="border-t border-slate-100 pt-4 space-y-4">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">CTA Button Action</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Teks Tombol</label>
                            <input type="text" value="${sec.btn_text || ''}" oninput="updateEditingSection('btn_text', this.value)" class="w-full rounded-xl border-slate-200 text-xs bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Link URL Tombol</label>
                            <input type="text" value="${sec.btn_url || ''}" oninput="updateEditingSection('btn_url', this.value)" class="w-full rounded-xl border-slate-200 text-xs bg-white">
                        </div>
                    </div>
                </div>
            `;
        } else if (sec.type === 'stats') {
            formHtml += `
                <div class="border-t border-slate-100 pt-4 space-y-4">
                    <div class="flex justify-between items-center">
                        <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Daftar Angka</h4>
                        <button type="button" onclick="addStatsItem(${index})" class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-bold">+ Tambah</button>
                    </div>
                    <div class="space-y-3" id="stats-items-editor">
                        ${(sec.items || []).map((item, subIdx) => `
                            <div class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                <input type="text" value="${item.number}" oninput="updateStatsItem(${index}, ${subIdx}, 'number', this.value)" class="rounded-xl border-slate-200 text-xs font-black py-1 px-2 bg-white max-w-[80px]" placeholder="10+">
                                <input type="text" value="${item.label}" oninput="updateStatsItem(${index}, ${subIdx}, 'label', this.value)" class="rounded-xl border-slate-200 text-xs py-1 px-2 bg-white flex-grow" placeholder="Label">
                                <button type="button" onclick="deleteStatsItem(${index}, ${subIdx})" class="text-red-500 hover:text-red-700 text-xs font-bold px-1">✕</button>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        } else if (sec.type === 'faq') {
            formHtml += `
                <div class="border-t border-slate-100 pt-4 space-y-4">
                    <div class="flex justify-between items-center">
                        <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Accordion Pertanyaan</h4>
                        <button type="button" onclick="addFaqItem(${index})" class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-bold">+ Tambah</button>
                    </div>
                    <div class="space-y-4" id="faq-items-editor">
                        ${(sec.faqs || []).map((faq, subIdx) => `
                            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] text-slate-400 font-bold">Faq #${subIdx+1}</span>
                                    <button type="button" onclick="deleteFaqItem(${index}, ${subIdx})" class="text-red-500 hover:text-red-700 text-xs font-bold">✕ Hapus</button>
                                </div>
                                <input type="text" value="${faq.q}" oninput="updateFaqItem(${index}, ${subIdx}, 'q', this.value)" class="w-full rounded-xl border-slate-200 text-xs bg-white font-bold" placeholder="Pertanyaan">
                                <textarea rows="2" oninput="updateFaqItem(${index}, ${subIdx}, 'a', this.value)" class="w-full rounded-xl border-slate-200 text-xs bg-white" placeholder="Jawaban">${faq.a}</textarea>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        } else if (sec.type === 'testimonials') {
            formHtml += `
                <div class="border-t border-slate-100 pt-4 space-y-4">
                    <div class="flex justify-between items-center">
                        <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Daftar Testimoni</h4>
                        <button type="button" onclick="addTestimonialItem(${index})" class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-bold">+ Tambah</button>
                    </div>
                    <div class="space-y-4" id="testimonial-items-editor">
                        ${(sec.items || []).map((testi, subIdx) => `
                            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] text-slate-400 font-bold">Testimoni #${subIdx+1}</span>
                                    <button type="button" onclick="deleteTestimonialItem(${index}, ${subIdx})" class="text-red-500 hover:text-red-700 text-xs font-bold">✕ Hapus</button>
                                </div>
                                <textarea rows="2" oninput="updateTestimonialItem(${index}, ${subIdx}, 'quote', this.value)" class="w-full rounded-xl border-slate-200 text-xs bg-white italic" placeholder="Kutipan Quote">${testi.quote || ''}</textarea>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" value="${testi.author || ''}" oninput="updateTestimonialItem(${index}, ${subIdx}, 'author', this.value)" class="rounded-xl border-slate-200 text-[10px] bg-white" placeholder="Nama Tokoh">
                                    <input type="text" value="${testi.role || ''}" oninput="updateTestimonialItem(${index}, ${subIdx}, 'role', this.value)" class="rounded-xl border-slate-200 text-[10px] bg-white" placeholder="Jabatan">
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        } else if (sec.type === 'sponsors') {
            formHtml += `
                <div class="border-t border-slate-100 pt-4 space-y-4">
                    <div class="flex justify-between items-center">
                        <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Mitra & Logo Sponsor</h4>
                        <button type="button" onclick="addSponsorItem(${index})" class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-bold">+ Tambah</button>
                    </div>
                    <div class="space-y-2" id="sponsor-items-editor">
                        ${(sec.logos || []).map((logo, subIdx) => `
                            <div class="flex items-center gap-2 bg-slate-50 p-2 rounded-xl border border-slate-100">
                                <input type="text" value="${logo}" oninput="updateSponsorItem(${index}, ${subIdx}, this.value)" class="rounded-xl border-slate-200 text-xs py-1 px-2 bg-white flex-grow" placeholder="Nama Logo">
                                <button type="button" onclick="deleteSponsorItem(${index}, ${subIdx})" class="text-red-500 hover:text-red-700 text-xs font-bold px-1">✕</button>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        contentArea.innerHTML = formHtml;
        
        // Slide drawer open
        const drawer = document.getElementById('section-drawer');
        drawer.classList.remove('hidden');
        setTimeout(() => {
            drawer.firstElementChild.classList.remove('translate-x-full');
            drawer.firstElementChild.classList.add('translate-x-0');
        }, 50);
    }

    function closeDrawer() {
        const drawer = document.getElementById('section-drawer');
        drawer.firstElementChild.classList.remove('translate-x-0');
        drawer.firstElementChild.classList.add('translate-x-full');
        setTimeout(() => {
            drawer.classList.add('hidden');
        }, 200);
        editingSectionIndex = null;
        renderSections();
    }

    function updateEditingSection(key, val) {
        if (editingSectionIndex !== null) {
            layoutConfig.sections[editingSectionIndex][key] = val;
            markDirty();
        }
    }

    // Sub-item editors inside drawer
    function addStatsItem(index) {
        if (!layoutConfig.sections[index].items) layoutConfig.sections[index].items = [];
        layoutConfig.sections[index].items.push({ number: '10+', label: 'Label Baru' });
        markDirty();
        openSectionDrawer(index);
    }
    function updateStatsItem(index, subIdx, key, val) {
        layoutConfig.sections[index].items[subIdx][key] = val;
        markDirty();
    }
    function deleteStatsItem(index, subIdx) {
        layoutConfig.sections[index].items.splice(subIdx, 1);
        markDirty();
        openSectionDrawer(index);
    }

    function addFaqItem(index) {
        if (!layoutConfig.sections[index].faqs) layoutConfig.sections[index].faqs = [];
        layoutConfig.sections[index].faqs.push({ q: 'Pertanyaan Baru?', a: 'Tulis jawaban di sini.' });
        markDirty();
        openSectionDrawer(index);
    }
    function updateFaqItem(index, subIdx, key, val) {
        layoutConfig.sections[index].faqs[subIdx][key] = val;
        markDirty();
    }
    function deleteFaqItem(index, subIdx) {
        layoutConfig.sections[index].faqs.splice(subIdx, 1);
        markDirty();
        openSectionDrawer(index);
    }

    function addTestimonialItem(index) {
        if (!layoutConfig.sections[index].items) layoutConfig.sections[index].items = [];
        layoutConfig.sections[index].items.push({ quote: 'Tulis quote testimoni.', author: 'Nama Tokoh', role: 'Jabatan' });
        markDirty();
        openSectionDrawer(index);
    }
    function updateTestimonialItem(index, subIdx, key, val) {
        layoutConfig.sections[index].items[subIdx][key] = val;
        markDirty();
    }
    function deleteTestimonialItem(index, subIdx) {
        layoutConfig.sections[index].items.splice(subIdx, 1);
        markDirty();
        openSectionDrawer(index);
    }

    function addSponsorItem(index) {
        if (!layoutConfig.sections[index].logos) layoutConfig.sections[index].logos = [];
        layoutConfig.sections[index].logos.push('Logo Baru');
        markDirty();
        openSectionDrawer(index);
    }
    function updateSponsorItem(index, subIdx, val) {
        layoutConfig.sections[index].logos[subIdx] = val;
        markDirty();
    }
    function deleteSponsorItem(index, subIdx) {
        layoutConfig.sections[index].logos.splice(subIdx, 1);
        markDirty();
        openSectionDrawer(index);
    }

    // ==================== LIVE COLORS INJECTOR ====================
    function liveUpdateColors() {
        markDirty();
        const primary = document.getElementById('primary_color').value;
        const secondary = document.getElementById('secondary_color').value;
        const accent = document.getElementById('accent_color').value;

        const iframe = document.getElementById('preview-iframe');
        if (iframe && iframe.contentWindow) {
            const doc = iframe.contentDocument || iframe.contentWindow.document;
            if (doc && doc.documentElement) {
                doc.documentElement.style.setProperty('--color-primary', primary);
                doc.documentElement.style.setProperty('--color-secondary', secondary);
                doc.documentElement.style.setProperty('--color-accent', accent);
            }
        }
    }

    function refreshIframe() {
        const iframe = document.getElementById('preview-iframe');
        if (iframe) {
            iframe.style.opacity = '0.3';
            iframe.src = iframe.src;
        }
    }

    function onIframeLoaded() {
        const iframe = document.getElementById('preview-iframe');
        if (iframe) {
            iframe.style.opacity = '1';
            // Sync colors immediately after reload
            liveUpdateColors();
        }
    }

    // ==================== SUBMIT PROCESSING ====================
    function prepareSubmit() {
        // Collect dynamic values into configuration JSON
        layoutConfig.design_system.radius = document.getElementById('ds_radius').value;
        layoutConfig.design_system.border_width = document.getElementById('ds_border').value;

        const isPopupActive = document.getElementById('pop_active').value === 'true';
        layoutConfig.popup_banner.is_active = isPopupActive;
        layoutConfig.popup_banner.type = document.getElementById('pop_type').value;
        layoutConfig.popup_banner.trigger_mode = document.getElementById('pop_trigger').value;
        layoutConfig.popup_banner.start_date = document.getElementById('pop_start').value;
        layoutConfig.popup_banner.end_date = document.getElementById('pop_end').value;
        layoutConfig.popup_banner.cta_text = document.getElementById('pop_cta_text').value;
        layoutConfig.popup_banner.cta_url = document.getElementById('pop_cta_url').value;

        // Set form payload values
        document.getElementById('layout_config_json_input').value = JSON.stringify(layoutConfig);
        document.getElementById('menus_json_input').value = JSON.stringify(menus);
    }
</script>
@endsection
