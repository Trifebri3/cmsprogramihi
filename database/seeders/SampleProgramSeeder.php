<?php

namespace Database\Seeders;

use App\Models\Theme;
use App\Models\Program;
use App\Models\Menu;
use App\Models\Content;
use App\Models\Period;
use App\Models\Management;
use App\Models\Album;
use App\Models\Photo;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::where('email', 'superadmin@ppms.com')->first();
        if (!$author) {
            return;
        }

        // ==========================================
        // 1. SEED PROGRAM 1: Generaz Berbakti
        // ==========================================
        $theme1 = Theme::create([
            'name' => 'Generaz Sage Theme',
            'primary_color' => '#4F6F52', // Sage Green
            'secondary_color' => '#F5F7F8', // Beige
            'accent_color' => '#D80032', // Crimson highlight
            'font_heading' => 'Poppins',
            'font_body' => 'Inter',
            'layout_config' => [
                'navbar' => 'center',
                'footer' => 'modern',
                'hero' => 'fullwidth',
            ],
            'custom_css' => '/* Custom styling for Generaz */ .theme-primary-bg { border-radius: 12px; }',
        ]);

        $program1 = Program::create([
            'theme_id' => $theme1->id,
            'name' => 'Generaz Berbakti',
            'slug' => 'generaz-berbakti',
            'subdomain' => 'generaz',
            'custom_domain' => null,
            'logo_path' => null,
            'banner_path' => null,
            'status' => 'active',
        ]);

        // Menus for Generaz
        Menu::create([
            'program_id' => $program1->id,
            'name' => 'Tentang Kami',
            'slug' => 'tentang-kami',
            'url' => 'page/tentang-kami',
            'order_no' => 1,
        ]);
        Menu::create([
            'program_id' => $program1->id,
            'name' => 'Layanan Utama',
            'slug' => 'layanan',
            'url' => '#',
            'order_no' => 2,
        ]);

        // Contents for Generaz
        Content::create([
            'program_id' => $program1->id,
            'author_id' => $author->id,
            'type' => 'page',
            'title' => 'Tentang Kami',
            'slug' => 'tentang-kami',
            'content_blocks' => [
                [
                    'type' => 'hero',
                    'title' => 'Mengabdi Tanpa Batas',
                    'subtitle' => 'Sejarah singkat dan mimpi besar Generaz Berbakti.',
                ],
                [
                    'type' => 'text',
                    'heading' => 'Misi Sosial Generaz',
                    'body' => '<p>Generaz Berbakti didirikan pada tahun 2024 oleh sekelompok mahasiswa yang ingin membawa perubahan langsung ke masyarakat marginal. Fokus kami meliputi pendidikan non-formal dan penyaluran bantuan tanggap bencana.</p>',
                ]
            ],
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => 'Tentang Kami - Generaz Berbakti',
            'seo_description' => 'Mengenal sejarah dan program kemanusiaan Generaz Berbakti.',
        ]);

        Content::create([
            'program_id' => $program1->id,
            'author_id' => $author->id,
            'type' => 'post',
            'title' => 'Aksi Kelas Berbagi Desa Sukamaju',
            'slug' => 'aksi-kelas-berbagi',
            'content_blocks' => [
                [
                    'type' => 'hero',
                    'title' => 'Kelas Literasi Anak Desa',
                    'subtitle' => 'Membuka gerbang dunia melalui buku dan bercerita.',
                ],
                [
                    'type' => 'text',
                    'heading' => 'Perjalanan Relawan',
                    'body' => '<p>Minggu ini tim relawan Generaz melakukan aksi sosial mengajar membaca untuk 45 anak di pinggiran Sukamaju. Senyum ceria terpancar jelas dari raut muka mereka.</p>',
                ]
            ],
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => 'Aksi Mengajar Sukamaju - Generaz Berbakti',
        ]);

        Content::create([
            'program_id' => $program1->id,
            'author_id' => $author->id,
            'type' => 'announcement',
            'title' => 'Open Recruitment Relawan Batch V',
            'slug' => 'oprec-batch-v',
            'content_blocks' => [
                [
                    'type' => 'text',
                    'heading' => 'Bergabung Bersama Kami',
                    'body' => '<p>Pendaftaran dibuka tanggal 10 - 25 Juli 2026. Persyaratan: berjiwa sosial, bersedia turun lapangan, berdomisili di Bandung.</p>',
                ]
            ],
            'status' => 'published',
            'published_at' => now(),
        ]);

        Content::create([
            'program_id' => $program1->id,
            'author_id' => $author->id,
            'type' => 'event',
            'title' => 'Seminar Pendidikan Karakter Remaja',
            'slug' => 'seminar-karakter',
            'content_blocks' => [
                [
                    'type' => 'cta',
                    'title' => 'Daftar Seminar Karakter Remaja',
                    'subtitle' => 'Tanggal 18 Juli 2026 via Zoom Meeting.',
                    'button_text' => 'Daftar Zoom',
                    'button_url' => 'https://zoom.us',
                ]
            ],
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Management Periods
        $period1 = Period::create([
            'program_id' => $program1->id,
            'name' => 'Dewan Pengurus 2026',
            'year_start' => 2026,
            'year_end' => 2027,
            'status' => 'active',
        ]);

        // Seed loggable User accounts for testing
        $adminAccount = User::create([
            'name' => 'Ahmad Hidayat (Admin)',
            'email' => 'admin@generaz.com',
            'password' => bcrypt('password'),
            'program_id' => $program1->id,
        ]);
        $adminAccount->assignRole('program-admin');

        $editorAccount = User::create([
            'name' => 'Siti Aminah (Editor)',
            'email' => 'editor@generaz.com',
            'password' => bcrypt('password'),
            'program_id' => $program1->id,
        ]);
        $editorAccount->assignRole('editor');

        Management::create([
            'period_id' => $period1->id,
            'name' => 'Ahmad Hidayat',
            'position' => 'Ketua Umum',
            'bio' => 'Senang berkolaborasi untuk menyebarkan dampak positif bagi anak muda.',
            'linkedin' => 'https://linkedin.com',
            'instagram' => 'https://instagram.com',
            'order_no' => 1,
        ]);
        Management::create([
            'period_id' => $period1->id,
            'name' => 'Siti Aminah',
            'position' => 'Sekretaris Jenderal',
            'bio' => 'Berkomitmen menjaga efektivitas administrasi dan keberlanjutan program.',
            'linkedin' => 'https://linkedin.com',
            'instagram' => 'https://instagram.com',
            'order_no' => 2,
        ]);

        // Album & Photos
        $album1 = Album::create([
            'program_id' => $program1->id,
            'title' => 'Dokumentasi Aksi Bandung 2026',
            'description' => 'Kompilasi foto-foto kegiatan belajar dan pembagian sembako.',
        ]);

        Photo::create([
            'album_id' => $album1->id,
            'file_path' => 'images/logogelap.png', // Temporary use exist images
            'caption' => 'Rapat Koordinasi Relawan PPMS',
        ]);

        // Documents
        Document::create([
            'program_id' => $program1->id,
            'title' => 'SOP Rekrutmen Relawan 2026.pdf',
            'description' => 'Standar operasional prosedur penyaringan dan pembekalan relawan.',
            'file_path' => 'documents/sop-relawan.pdf',
            'category' => 'public',
            'status' => 'active',
        ]);


        // ==========================================
        // 2. SEED PROGRAM 2: Sekolah Tangguh
        // ==========================================
        $theme2 = Theme::create([
            'name' => 'Sekolah Navy Theme',
            'primary_color' => '#1B3C73', // Deep Navy
            'secondary_color' => '#FFF7F6', // Pale pink beige
            'accent_color' => '#FFB5B5', // Warm Gold/Rose
            'font_heading' => 'Playfair Display',
            'font_body' => 'Roboto',
            'layout_config' => [
                'navbar' => 'left',
                'footer' => 'simple',
                'hero' => 'split',
            ],
            'custom_css' => 'body { letter-spacing: 0.025em; }',
        ]);

        $program2 = Program::create([
            'theme_id' => $theme2->id,
            'name' => 'Sekolah Tangguh',
            'slug' => 'sekolah-tangguh',
            'subdomain' => 'sekolahtangguh',
            'custom_domain' => null,
            'logo_path' => null,
            'banner_path' => null,
            'status' => 'active',
        ]);

        // Menus for Sekolah Tangguh
        Menu::create([
            'program_id' => $program2->id,
            'name' => 'Kurikulum',
            'slug' => 'kurikulum',
            'url' => 'page/kurikulum',
            'order_no' => 1,
        ]);

        // Contents for Sekolah Tangguh
        Content::create([
            'program_id' => $program2->id,
            'author_id' => $author->id,
            'type' => 'page',
            'title' => 'Kurikulum Merdeka Mandiri',
            'slug' => 'kurikulum',
            'content_blocks' => [
                [
                    'type' => 'hero',
                    'title' => 'Kurikulum Tangguh & Adaptif',
                    'subtitle' => 'Mempersiapkan lulusan yang sigap menghadapi tantangan global.',
                ],
                [
                    'type' => 'text',
                    'heading' => 'Pilar Pendidikan Kami',
                    'body' => '<p>Sekolah Tangguh menggunakan kurikulum berbasis kompetensi dengan penekanan pada literasi digital, sains terapan, dan ketahanan mitigasi bencana.</p>',
                ]
            ],
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => 'Kurikulum Sekolah Tangguh',
        ]);

        Content::create([
            'program_id' => $program2->id,
            'author_id' => $author->id,
            'type' => 'post',
            'title' => 'Prestasi Juara OSN Robotika',
            'slug' => 'prestasi-robotika',
            'content_blocks' => [
                [
                    'type' => 'text',
                    'heading' => 'Piala Emas Robotika',
                    'body' => '<p>Siswa Sekolah Tangguh berhasil menyabet gelar Juara 1 Nasional kategori Maze Solver di Olimpiade Sains Robotika Nasional.</p>',
                ]
            ],
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Management Periods
        $period2 = Period::create([
            'program_id' => $program2->id,
            'name' => 'Staf Pengajar Akademik 2026',
            'year_start' => 2026,
            'year_end' => 2027,
            'status' => 'active',
        ]);

        Management::create([
            'period_id' => $period2->id,
            'name' => 'Dr. Adiwijaya',
            'position' => 'Kepala Sekolah',
            'bio' => 'Pendidikan adalah senjata paling ampuh untuk mengubah masa depan anak bangsa.',
            'linkedin' => 'https://linkedin.com',
            'instagram' => 'https://instagram.com',
            'order_no' => 1,
        ]);
    }
}
