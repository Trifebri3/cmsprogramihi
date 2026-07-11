<?php

namespace App\Http\Controllers\ProgramAdmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected function getProgramId()
    {
        $user = auth()->user();
        if ($user->hasRole('super-admin')) {
            $sessionProgramId = session('active_program_id');
            if (!$sessionProgramId) {
                $firstProgram = \App\Models\Program::first();
                if ($firstProgram) {
                    session(['active_program_id' => $firstProgram->id]);
                    return $firstProgram->id;
                }
            }
            return $sessionProgramId;
        }
        return $user->program_id;
    }

    public function index()
    {
        $programId = $this->getProgramId();
        if (!$programId) {
            abort(403, 'Program context not resolved.');
        }

        $categories = Category::where('program_id', $programId)->get();
        return view('program.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('program.categories.create');
    }

    public function store(Request $request)
    {
        $programId = $this->getProgramId();
        if (!$programId) {
            abort(403, 'Program context not resolved.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->slug);

        // Check uniqueness per program
        $exists = Category::where('program_id', $programId)->where('slug', $slug)->exists();
        if ($exists) {
            return back()->withErrors(['slug' => 'Slug sudah digunakan untuk kategori lain pada program ini.'])->withInput();
        }

        Category::create([
            'program_id' => $programId,
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('cms.categories.index')->with('status', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $programId = $this->getProgramId();

        if ($category->program_id !== $programId && !auth()->user()->hasRole('super-admin')) {
            abort(403);
        }

        return view('program.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $programId = $this->getProgramId();

        if ($category->program_id !== $programId && !auth()->user()->hasRole('super-admin')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->slug);

        // Check uniqueness excluding current record
        $exists = Category::where('program_id', $programId)
            ->where('slug', $slug)
            ->where('id', '!=', $id)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['slug' => 'Slug sudah digunakan untuk kategori lain pada program ini.'])->withInput();
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('cms.categories.index')->with('status', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $programId = $this->getProgramId();

        if ($category->program_id !== $programId && !auth()->user()->hasRole('super-admin')) {
            abort(403);
        }

        $category->delete();
        return redirect()->route('cms.categories.index')->with('status', 'Category deleted successfully.');
    }
}
