<?php

namespace App\Http\Controllers\ProgramAdmin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
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
            abort(403, 'Program context not defined.');
        }

        $contents = Content::where('program_id', $programId)->with('author')->get();
        return view('program.contents.index', compact('contents'));
    }

    public function create()
    {
        $programId = $this->getProgramId();
        $categories = \App\Models\Category::where('program_id', $programId)->get();
        return view('program.contents.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $programId = $this->getProgramId();
        if (!$programId) {
            abort(403, 'Program context not defined.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'type' => 'required|string|in:page,post,announcement,event',
            'status' => 'required|string|in:draft,published,archived',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'content_blocks_json' => 'nullable|string',
        ]);

        $status = $request->status;

        // Enforce publishing restrictions from ContentPolicy
        if ($status === 'published' && !auth()->user()->can('publish', Content::class)) {
            $status = 'draft';
        }

        // Parse and process dynamic content blocks
        $blocksData = [];
        if ($request->filled('content_blocks_json')) {
            $blocksData = json_decode($request->input('content_blocks_json'), true);
        } elseif ($request->filled('content_text')) {
            $blocksData = [
                [
                    'id' => 'b_text_legacy',
                    'type' => 'text',
                    'heading' => '',
                    'body' => $request->content_text
                ]
            ];
        }

        if (!is_array($blocksData)) {
            $blocksData = [];
        }

        $rebuildBlocks = [];
        foreach ($blocksData as $block) {
            $blockId = $block['id'] ?? uniqid('b_');
            $type = $block['type'] ?? 'text';

            if ($type === 'hero') {
                $coverUrl = $block['cover_url'] ?? null;
                $inputName = 'hero_cover_' . $blockId;
                if ($request->hasFile($inputName)) {
                    $path = $request->file($inputName)->store('covers', 'public');
                    $coverUrl = asset('storage/' . $path);
                }
                $rebuildBlocks[] = [
                    'id' => $blockId,
                    'type' => 'hero',
                    'title' => $block['title'] ?? '',
                    'subtitle' => $block['subtitle'] ?? '',
                    'cover_url' => $coverUrl
                ];
            } elseif ($type === 'text') {
                $rebuildBlocks[] = [
                    'id' => $blockId,
                    'type' => 'text',
                    'heading' => $block['heading'] ?? '',
                    'body' => $block['body'] ?? ''
                ];
            } elseif ($type === 'video') {
                $rebuildBlocks[] = [
                    'id' => $blockId,
                    'type' => 'video',
                    'title' => $block['title'] ?? '',
                    'video_url' => $block['video_url'] ?? ''
                ];
            } elseif ($type === 'button') {
                $rebuildBlocks[] = [
                    'id' => $blockId,
                    'type' => 'button',
                    'button_text' => $block['button_text'] ?? '',
                    'button_url' => $block['button_url'] ?? ''
                ];
            } elseif ($type === 'cta') {
                $rebuildBlocks[] = [
                    'id' => $blockId,
                    'type' => 'cta',
                    'title' => $block['title'] ?? '',
                    'subtitle' => $block['subtitle'] ?? '',
                    'button_text' => $block['button_text'] ?? '',
                    'button_url' => $block['button_url'] ?? ''
                ];
            } elseif ($type === 'gallery') {
                $existingImages = [];
                $fileInput = 'gallery_files_' . $blockId;
                if ($request->hasFile($fileInput)) {
                    foreach ($request->file($fileInput) as $file) {
                        $path = $file->store('articles', 'public');
                        $existingImages[] = asset('storage/' . $path);
                    }
                }

                $rebuildBlocks[] = [
                    'id' => $blockId,
                    'type' => 'gallery',
                    'title' => $block['title'] ?? '',
                    'images' => $existingImages
                ];
            }
        }

        $content = Content::create([
            'program_id' => $programId,
            'author_id' => auth()->id(),
            'type' => $request->type,
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'content_blocks' => $rebuildBlocks,
            'status' => $status,
            'published_at' => $status === 'published' ? now() : null,
        ]);

        $content->categories()->sync($request->input('categories', []));

        return redirect()->route('cms.contents.index')->with('status', 'Konten berhasil disimpan.');
    }

    public function edit($id)
    {
        $content = Content::findOrFail($id);
        \Illuminate\Support\Facades\Gate::authorize('update', $content);

        $programId = $this->getProgramId();
        $categories = \App\Models\Category::where('program_id', $programId)->get();

        return view('program.contents.edit', compact('content', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $content = Content::findOrFail($id);
        \Illuminate\Support\Facades\Gate::authorize('update', $content);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'status' => 'required|string|in:draft,published,archived',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'content_blocks_json' => 'required|string',
        ]);

        $status = $request->status;

        // Enforce publishing restrictions
        if ($status === 'published' && !auth()->user()->can('publish', Content::class)) {
            $status = 'draft';
        }

        // Parse and process dynamic content blocks
        $blocksData = json_decode($request->input('content_blocks_json'), true);
        if (!is_array($blocksData)) {
            $blocksData = [];
        }

        $rebuildBlocks = [];
        foreach ($blocksData as $block) {
            $blockId = $block['id'] ?? uniqid('b_');
            $type = $block['type'] ?? 'text';

            if ($type === 'hero') {
                $coverUrl = $block['cover_url'] ?? null;
                $inputName = 'hero_cover_' . $blockId;
                if ($request->hasFile($inputName)) {
                    // Try to delete old cover
                    if ($coverUrl) {
                        $pathParts = explode('/storage/', $coverUrl);
                        if (count($pathParts) > 1) {
                            Storage::disk('public')->delete($pathParts[1]);
                        }
                    }
                    $path = $request->file($inputName)->store('covers', 'public');
                    $coverUrl = asset('storage/' . $path);
                }
                $rebuildBlocks[] = [
                    'id' => $blockId,
                    'type' => 'hero',
                    'title' => $block['title'] ?? '',
                    'subtitle' => $block['subtitle'] ?? '',
                    'cover_url' => $coverUrl
                ];
            } elseif ($type === 'text') {
                $rebuildBlocks[] = [
                    'id' => $blockId,
                    'type' => 'text',
                    'heading' => $block['heading'] ?? '',
                    'body' => $block['body'] ?? ''
                ];
            } elseif ($type === 'video') {
                $rebuildBlocks[] = [
                    'id' => $blockId,
                    'type' => 'video',
                    'title' => $block['title'] ?? '',
                    'video_url' => $block['video_url'] ?? ''
                ];
            } elseif ($type === 'button') {
                $rebuildBlocks[] = [
                    'id' => $blockId,
                    'type' => 'button',
                    'button_text' => $block['button_text'] ?? '',
                    'button_url' => $block['button_url'] ?? ''
                ];
            } elseif ($type === 'cta') {
                $rebuildBlocks[] = [
                    'id' => $blockId,
                    'type' => 'cta',
                    'title' => $block['title'] ?? '',
                    'subtitle' => $block['subtitle'] ?? '',
                    'button_text' => $block['button_text'] ?? '',
                    'button_url' => $block['button_url'] ?? ''
                ];
            } elseif ($type === 'gallery') {
                $existingImages = $block['images'] ?? [];
                
                // Handle deletions
                $deleteInput = 'delete_images_' . $blockId;
                if ($request->filled($deleteInput)) {
                    $toDelete = $request->input($deleteInput);
                    $existingImages = array_values(array_filter($existingImages, function ($img) use ($toDelete) {
                        if (in_array($img, $toDelete)) {
                            $pathParts = explode('/storage/', $img);
                            if (count($pathParts) > 1) {
                                Storage::disk('public')->delete($pathParts[1]);
                            }
                            return false;
                        }
                        return true;
                    }));
                }

                // Upload new gallery photos
                $fileInput = 'gallery_files_' . $blockId;
                if ($request->hasFile($fileInput)) {
                    foreach ($request->file($fileInput) as $file) {
                        $path = $file->store('articles', 'public');
                        $existingImages[] = asset('storage/' . $path);
                    }
                }

                $rebuildBlocks[] = [
                    'id' => $blockId,
                    'type' => 'gallery',
                    'title' => $block['title'] ?? '',
                    'images' => $existingImages
                ];
            }
        }

        $content->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'content_blocks' => $rebuildBlocks,
            'status' => $status,
            'published_at' => $status === 'published' ? now() : ($content->published_at ?? null),
        ]);

        $content->categories()->sync($request->input('categories', []));

        return redirect()->route('cms.contents.index')->with('status', 'Konten berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $content = Content::findOrFail($id);
        \Illuminate\Support\Facades\Gate::authorize('delete', $content);

        $content->delete();
        return redirect()->route('cms.contents.index')->with('status', 'Content deleted successfully.');
    }
}
