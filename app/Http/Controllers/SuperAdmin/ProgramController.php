<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Theme;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::with('theme')->get();
        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        $themes = Theme::all();
        $users = \App\Models\User::all();
        return view('admin.programs.create', compact('themes', 'users'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:programs,slug|max:255',
            'subdomain' => 'nullable|string|unique:programs,subdomain|max:255',
            'custom_domain' => 'nullable|string|unique:programs,custom_domain|max:255',
            'template_type' => 'required|string|in:ngo,kampus,csr,custom',
            'status' => 'required|string|in:active,inactive',
            'admin_user_type' => 'nullable|string|in:existing,new',
        ];

        if ($request->input('admin_user_type') === 'new') {
            $rules['admin_name'] = 'required|string|max:255';
            $rules['admin_email'] = 'required|email|unique:users,email|max:255';
            $rules['admin_password'] = 'required|string|min:8|max:255';
        } else {
            $rules['admin_user_id'] = 'required|exists:users,id';
        }

        $request->validate($rules);

        // 1. Provision Theme based on selected template style
        $themeName = $request->name . ' Theme';
        $themeData = [
            'name' => $themeName,
            'primary_color' => '#2E7D32',
            'secondary_color' => '#FFFFFF',
            'accent_color' => '#FFB300',
            'font_heading' => 'Poppins',
            'font_body' => 'Inter',
            'layout_config' => [
                'navbar' => 'center',
                'footer' => 'modern',
                'hero' => 'fullwidth',
                'template_layout' => $request->template_type,
            ],
        ];

        if ($request->template_type === 'ngo') {
            $themeData['primary_color'] = '#4F6F52'; // Sage Green
            $themeData['secondary_color'] = '#F5F7F8';
            $themeData['accent_color'] = '#D80032';
        } elseif ($request->template_type === 'kampus') {
            $themeData['primary_color'] = '#1B3C73'; // Navy
            $themeData['secondary_color'] = '#FFF7F6';
            $themeData['accent_color'] = '#FFB5B5';
            $themeData['font_heading'] = 'Playfair Display';
            $themeData['font_body'] = 'Roboto';
        } elseif ($request->template_type === 'csr') {
            $themeData['primary_color'] = '#008080'; // Teal
            $themeData['secondary_color'] = '#FFFFFF';
            $themeData['accent_color'] = '#FFA500';
            $themeData['font_heading'] = 'Montserrat';
            $themeData['font_body'] = 'Open Sans';
        }

        $theme = Theme::create($themeData);

        // 2. Create the Program
        $program = Program::create([
            'theme_id' => $theme->id,
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'subdomain' => $request->subdomain ? Str::slug($request->subdomain) : null,
            'custom_domain' => $request->custom_domain,
            'status' => $request->status,
        ]);

        // 3. Seed Default Menus based on template type
        $menus = [];
        if ($request->template_type === 'ngo') {
            $menus = [
                ['name' => 'Home', 'url' => '/'],
                ['name' => 'Tentang', 'url' => 'page/tentang-kami'],
                ['name' => 'Program Kerja', 'url' => 'posts/kegiatan'],
                ['name' => 'Galeri', 'url' => 'gallery'],
                ['name' => 'Pengurus', 'url' => 'management'],
                ['name' => 'Dokumen', 'url' => 'documents'],
            ];
        } elseif ($request->template_type === 'kampus') {
            $menus = [
                ['name' => 'Home', 'url' => '/'],
                ['name' => 'Profil', 'url' => 'page/profil-kampus'],
                ['name' => 'Dosen & Staf', 'url' => 'management'],
                ['name' => 'Mahasiswa', 'url' => 'page/mahasiswa'],
                ['name' => 'Berita', 'url' => 'posts/berita'],
                ['name' => 'Dokumen Akademik', 'url' => 'documents'],
            ];
        } elseif ($request->template_type === 'csr') {
            $menus = [
                ['name' => 'Home', 'url' => '/'],
                ['name' => 'About Us', 'url' => 'page/about'],
                ['name' => 'Initiatives', 'url' => 'posts/initiatives'],
                ['name' => 'Reports', 'url' => 'documents'],
            ];
        }

        foreach ($menus as $idx => $m) {
            Menu::create([
                'program_id' => $program->id,
                'name' => $m['name'],
                'slug' => Str::slug($m['name']),
                'url' => $m['url'],
                'order_no' => $idx + 1,
                'status' => 'active',
            ]);
        }

        // 4. Assign Admin User to the Program
        if ($request->input('admin_user_type') === 'new') {
            $adminUser = \App\Models\User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => bcrypt($request->admin_password),
                'program_id' => $program->id,
            ]);
        } else {
            $adminUser = \App\Models\User::findOrFail($request->admin_user_id);
            $adminUser->update(['program_id' => $program->id]);
        }
        
        $adminUser->assignRole('program-admin');

        return redirect()->route('dashboard')->with('status', 'Program provisioned successfully with selected template theme, default menus, and assigned Program Admin.');
    }

    public function edit($id)
    {
        $program = Program::findOrFail($id);
        $themes = Theme::all();
        $users = \App\Models\User::all();
        $currentAdmin = \App\Models\User::where('program_id', $program->id)
            ->role('program-admin')
            ->first();
        return view('admin.programs.edit', compact('program', 'themes', 'users', 'currentAdmin'));
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:programs,slug,' . $id,
            'subdomain' => 'nullable|string|max:255|unique:programs,subdomain,' . $id,
            'custom_domain' => 'nullable|string|max:255|unique:programs,custom_domain,' . $id,
            'theme_id' => 'nullable|exists:themes,id',
            'admin_user_id' => 'required|exists:users,id',
            'status' => 'required|string|in:active,inactive',
        ]);

        $program->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'subdomain' => $request->subdomain ? Str::slug($request->subdomain) : null,
            'custom_domain' => $request->custom_domain,
            'theme_id' => $request->theme_id,
            'status' => $request->status,
        ]);

        // Reassign Program Admin
        // 1. Remove old admin role and program reference if changed
        $oldAdmin = \App\Models\User::where('program_id', $program->id)->role('program-admin')->first();
        if ($oldAdmin && $oldAdmin->id != $request->admin_user_id) {
            $oldAdmin->update(['program_id' => null]);
            $oldAdmin->removeRole('program-admin');
        }

        // 2. Set new admin
        $newAdmin = \App\Models\User::findOrFail($request->admin_user_id);
        $newAdmin->update(['program_id' => $program->id]);
        $newAdmin->assignRole('program-admin');

        return redirect()->route('dashboard')->with('status', 'Program updated successfully.');
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();
        return redirect()->route('dashboard')->with('status', 'Program deleted successfully.');
    }
}
