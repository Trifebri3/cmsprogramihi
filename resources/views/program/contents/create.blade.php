@extends('layouts.program-admin.app')

@section('header')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
    <div>
        <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
            Tulis Konten dan Halaman Baru
        </h2>
        <p class="text-xs text-slate-400 mt-1">Buat publikasi baru dengan susunan tata letak blok yang dinamis.</p>
    </div>
</div>
@endsection

@section('content')
<div class="py-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white border border-gray-150 rounded-3xl p-8 shadow-sm space-y-8">
        
        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm font-semibold">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('cms.contents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="content-form" x-data="{ contentType: 'post' }">
            @csrf
            
            <!-- Hidden input for blocks serializer JSON string -->
            <input type="hidden" name="content_blocks_json" id="content_blocks_json">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-6 border-b border-slate-100">
                <div class="md:col-span-2">
                    <label for="title" class="block text-xs font-black text-slate-700 uppercase mb-2">Judul Konten Utama</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full rounded-xl border-gray-255 text-sm focus:ring-indigo-500 bg-white" placeholder="Contoh: Aksi Tanam Pohon Berjalan Sukses">
                </div>

                <div>
                    <label for="slug" class="block text-xs font-black text-slate-700 uppercase mb-2">Slug URL</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required class="w-full rounded-xl border-gray-255 text-sm focus:ring-indigo-500 bg-white placeholder-slate-350" placeholder="contoh-artikel-baru">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-100">
                <div>
                    <label for="type" class="block text-xs font-black text-slate-700 uppercase mb-2">Tipe Konten</label>
                    <select name="type" id="type" required class="w-full rounded-xl border-gray-250 text-sm focus:ring-indigo-500 bg-white font-bold" x-model="contentType">
                        <option value="post">Post (Berita / Artikel)</option>
                        <option value="page">Page (Halaman Statis)</option>
                        <option value="announcement">Announcement (Pengumuman)</option>
                        <option value="event">Event (Kegiatan / Seminar)</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-black text-slate-700 uppercase mb-2">Status Publikasi</label>
                    <select name="status" id="status" required class="w-full rounded-xl border-gray-205 text-sm focus:ring-indigo-500 bg-white font-bold">
                        <option value="draft">Simpan sebagai Draft</option>
                        @if(auth()->user()->can('publish', App\Models\Content::class))
                            <option value="published" selected>Terbitkan (Publish)</option>
                        @else
                            <option value="published" class="text-gray-300" disabled>Terbitkan (Akses Terkunci)</option>
                        @endif
                        <option value="archived">Arsip</option>
                    </select>
                </div>
            </div>

            @if($categories->count() > 0)
                <div class="pb-6 border-b border-slate-100" x-show="contentType === 'post'">
                    <label class="block text-xs font-black text-slate-700 uppercase mb-2">Pilih Kategori Artikel (Khusus Tipe Post)</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        @foreach($categories as $category)
                            <label class="inline-flex items-center text-xs font-bold text-slate-700 cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="rounded border-slate-200 text-indigo-600 focus:ring-indigo-500 shadow-sm">
                                <span class="ml-2">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- CONTENT BLOCKS BUILDER WORKSPACE -->
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-150 shadow-sm">
                    <div>
                        <h4 class="font-extrabold text-sm text-slate-900 uppercase">Visual Page Blocks Builder</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5">Susun halaman secara modular dengan menambahkan elemen teks, galeri, video, tombol, dan banner.</p>
                    </div>
                    
                    <div class="flex gap-2 items-center">
                        <select id="block-type-selector" class="h-10 py-2 px-3 rounded-xl border border-slate-200 text-xs font-bold bg-white focus:ring-indigo-500 text-slate-700 w-48">
                            <option value="text">Blok Teks / Paragraf</option>
                            <option value="gallery">Blok Galeri & Dokumentasi</option>
                            <option value="video">Embed Video Youtube</option>
                            <option value="button">Tombol Tautan Link</option>
                            <option value="cta">Spanduk Banner CTA</option>
                        </select>
                        <button type="button" onclick="addNewBlock()" class="h-10 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold transition active:scale-95 shrink-0 flex items-center justify-center">
                            Tambah Blok
                        </button>
                    </div>
                </div>

                <!-- Active Blocks Render Output Container -->
                <div id="blocks-workspace" class="space-y-6">
                    <!-- Javascript will populate layout blocks here -->
                </div>
            </div>

            <!-- Footer Save buttons -->
            <div class="flex justify-end gap-3 border-t border-gray-150 pt-6">
                <a href="{{ route('cms.contents.index') }}" class="px-5 py-2.5 bg-slate-50 text-slate-655 hover:bg-slate-100 rounded-xl text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                    Simpan dan Terbitkan Konten
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Initial content blocks list schema
    let blocks = [
        { id: 'b_hero_1', type: 'hero', title: '', subtitle: '', cover_url: '' },
        { id: 'b_text_1', type: 'text', heading: 'Pendahuluan', body: '' }
    ];

    document.addEventListener('DOMContentLoaded', () => {
        renderBlocks();
        
        // Sync JSON data before submission
        document.getElementById('content-form').addEventListener('submit', (e) => {
            document.getElementById('content_blocks_json').value = JSON.stringify(blocks);
        });
    });

    function renderBlocks() {
        const workspace = document.getElementById('blocks-workspace');
        workspace.innerHTML = '';

        blocks.forEach((block, index) => {
            const card = document.createElement('div');
            card.className = "bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden transition-all duration-200";
            
            // Header bar of block element card
            let orderControls = '';
            if (block.type !== 'hero') {
                orderControls = `
                    <div class="flex items-center gap-1">
                        <button type="button" onclick="moveBlock('${block.id}', -1)" class="p-1 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded transition font-bold" title="Pindahkan Keatas">Atas</button>
                        <button type="button" onclick="moveBlock('${block.id}', 1)" class="p-1 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded transition font-bold" title="Pindahkan Kebawah">Bawah</button>
                        <span class="text-slate-200 px-1">|</span>
                        <button type="button" onclick="deleteBlock('${block.id}')" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition font-bold text-xs" title="Hapus Blok">Hapus</button>
                    </div>
                `;
            }

            let blockLabel = '';
            if (block.type === 'hero') blockLabel = 'Banner Sampul dan Judul Atas (Hero)';
            else if (block.type === 'text') blockLabel = 'Teks Deskripsi / Paragraf';
            else if (block.type === 'gallery') blockLabel = 'Galeri Foto / Dokumentasi Kegiatan';
            else if (block.type === 'video') blockLabel = 'Embed Player Video YouTube';
            else if (block.type === 'button') blockLabel = 'Tombol Aksi / Tautan Link';
            else if (block.type === 'cta') blockLabel = 'Spanduk Ajakan (CTA Banner)';

            let fieldsHtml = '';
            if (block.type === 'hero') {
                fieldsHtml = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase">Sub Judul / Keterangan Banner</label>
                            <input type="text" class="w-full rounded-xl border-slate-200 text-xs mt-1 focus:ring-indigo-500" value="${block.subtitle || ''}" placeholder="Tulis rincian pendek di banner..." oninput="updateBlockValue('${block.id}', 'subtitle', this.value)">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase">File Gambar Sampul (Hero Cover)</label>
                            <input type="file" name="hero_cover_${block.id}" class="w-full text-xs mt-1.5 file:mr-2 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>
                `;
            } else if (block.type === 'text') {
                fieldsHtml = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase">Sub-Judul Paragraf (Opsional)</label>
                            <input type="text" class="w-full rounded-xl border-slate-200 text-xs mt-1 focus:ring-indigo-500" value="${block.heading || ''}" placeholder="Contoh: Latar Belakang & Sejarah" oninput="updateBlockValue('${block.id}', 'heading', this.value)">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase">Isi Paragraf / Body Text (Mendukung tag HTML)</label>
                            <textarea class="w-full rounded-xl border-slate-200 text-xs mt-1 focus:ring-indigo-500" rows="5" placeholder="Tulis narasi lengkap di sini..." oninput="updateBlockValue('${block.id}', 'body', this.value)">${block.body || ''}</textarea>
                        </div>
                    </div>
                `;
            } else if (block.type === 'gallery') {
                fieldsHtml = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase">Judul Galeri Dokumentasi</label>
                            <input type="text" class="w-full rounded-xl border-slate-200 text-xs mt-1 focus:ring-indigo-500" value="${block.title || ''}" placeholder="Contoh: Galeri Aksi Penanaman" oninput="updateBlockValue('${block.id}', 'title', this.value)">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase">Unggah File Foto Galeri (Bisa Pilih Banyak)</label>
                            <input type="file" name="gallery_files_${block.id}[]" multiple class="w-full text-xs mt-1.5 file:mr-2 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>
                `;
            } else if (block.type === 'video') {
                fieldsHtml = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase">Judul / Keterangan Video</label>
                            <input type="text" class="w-full rounded-xl border-slate-200 text-xs mt-1 focus:ring-indigo-500" value="${block.title || ''}" placeholder="Contoh: Rilis Liputan Video TV" oninput="updateBlockValue('${block.id}', 'title', this.value)">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase">Link URL Video Youtube</label>
                            <input type="text" class="w-full rounded-xl border-slate-200 text-xs mt-1 focus:ring-indigo-500" value="${block.video_url || ''}" placeholder="Contoh: https://www.youtube.com/watch?v=..." oninput="updateBlockValue('${block.id}', 'video_url', this.value)">
                        </div>
                    </div>
                `;
            } else if (block.type === 'button') {
                fieldsHtml = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase">Label Nama Tombol</label>
                            <input type="text" class="w-full rounded-xl border-slate-200 text-xs mt-1 focus:ring-indigo-500" value="${block.button_text || ''}" placeholder="Contoh: Registrasi Pendamping" oninput="updateBlockValue('${block.id}', 'button_text', this.value)">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase">Link Tautan URL Tombol</label>
                            <input type="text" class="w-full rounded-xl border-slate-200 text-xs mt-1 focus:ring-indigo-500" value="${block.button_url || ''}" placeholder="Contoh: https://forms.gle/..." oninput="updateBlockValue('${block.id}', 'button_url', this.value)">
                        </div>
                    </div>
                `;
            } else if (block.type === 'cta') {
                fieldsHtml = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase">Judul Spanduk Ajakan</label>
                                <input type="text" class="w-full rounded-xl border-slate-200 text-xs mt-1 focus:ring-indigo-500" value="${block.title || ''}" placeholder="Contoh: Mari Bergabung Jadi Relawan Hijau!" oninput="updateBlockValue('${block.id}', 'title', this.value)">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase">Deskripsi Spanduk</label>
                                <input type="text" class="w-full rounded-xl border-slate-200 text-xs mt-1 focus:ring-indigo-500" value="${block.subtitle || ''}" placeholder="Tulis ajakan ringkas..." oninput="updateBlockValue('${block.id}', 'subtitle', this.value)">
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase">Label Tombol Banner</label>
                                <input type="text" class="w-full rounded-xl border-slate-200 text-xs mt-1 focus:ring-indigo-500" value="${block.button_text || ''}" placeholder="Contoh: Hubungi Kami" oninput="updateBlockValue('${block.id}', 'button_text', this.value)">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase">Tautan URL Tombol Banner</label>
                                <input type="text" class="w-full rounded-xl border-slate-200 text-xs mt-1 focus:ring-indigo-500" value="${block.button_url || ''}" placeholder="Contoh: https://wa.me/..." oninput="updateBlockValue('${block.id}', 'button_url', this.value)">
                            </div>
                        </div>
                    </div>
                `;
            }

            card.innerHTML = `
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center select-none">
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider">${blockLabel}</span>
                    ${orderControls}
                </div>
                <div class="p-6 bg-white">
                    ${fieldsHtml}
                </div>
            `;
            workspace.appendChild(card);
        });
    }

    function addNewBlock() {
        const selector = document.getElementById('block-type-selector');
        const type = selector.value;
        const id = 'b_' + type + '_' + Date.now();
        
        let newBlock = { id: id, type: type };
        if (type === 'text') { newBlock.heading = ''; newBlock.body = ''; }
        else if (type === 'gallery') { newBlock.title = ''; newBlock.images = []; }
        else if (type === 'video') { newBlock.title = ''; newBlock.video_url = ''; }
        else if (type === 'button') { newBlock.button_text = ''; newBlock.button_url = ''; }
        else if (type === 'cta') { newBlock.title = ''; newBlock.subtitle = ''; newBlock.button_text = ''; newBlock.button_url = ''; }

        blocks.push(newBlock);
        renderBlocks();
    }

    function updateBlockValue(id, key, val) {
        const idx = blocks.findIndex(b => b.id === id);
        if (idx !== -1) {
            blocks[idx][key] = val;
        }
    }

    function deleteBlock(id) {
        if (confirm('Apakah Anda yakin ingin menghapus blok ini dari layout halaman?')) {
            blocks = blocks.filter(b => b.id !== id);
            renderBlocks();
        }
    }

    function moveBlock(id, direction) {
        const index = blocks.findIndex(b => b.id === id);
        if (index === -1) return;

        const targetIndex = index + direction;
        
        // Block cannot swap below index 1 (since Hero at index 0 must be sticky)
        if (targetIndex < 1 || targetIndex >= blocks.length) return;

        // Swap elements
        const temp = blocks[index];
        blocks[index] = blocks[targetIndex];
        blocks[targetIndex] = temp;

        renderBlocks();
    }
</script>
@endsection
