<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;

class ArticleApiController extends Controller
{
    public function index(Request $request)
    {
        // Start querying published contents
        $query = Content::where('status', 'published')
            ->with(['program', 'author', 'categories']);

        // Filter by Program ID or Slug
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        } elseif ($request->filled('program_slug')) {
            $query->whereHas('program', function ($q) use ($request) {
                $q->where('slug', $request->program_slug);
            });
        }

        // Filter by Category ID or Slug
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        } elseif ($request->filled('category_slug')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.slug', $request->category_slug);
            });
        }

        // Filter by Content Type (post, page, announcement, event)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search by Title or Content Body
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content_blocks', 'like', "%{$search}%");
            });
        }

        // Sorting (default to published_at desc)
        $sortBy = $request->input('sort_by', 'published_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if (in_array($sortBy, ['published_at', 'created_at', 'title'])) {
            $query->orderBy($sortBy, in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'desc');
        } else {
            $query->orderBy('published_at', 'desc');
        }

        // Pagination
        $perPage = $request->input('per_page', 12);
        $paginator = $query->paginate($perPage);

        // Map response for cleaner public payload
        $formattedItems = collect($paginator->items())->map(function ($item) {
            // Extract preview text and gather all image assets
            $previewText = '';
            $coverUrl = null;
            $galleryImages = [];

            if (is_array($item->content_blocks)) {
                foreach ($item->content_blocks as $block) {
                    // Extract preview text
                    if (($block['type'] ?? '') === 'text' && !empty($block['body'])) {
                        if (empty($previewText)) {
                            $previewText = strip_tags($block['body']);
                        }
                    }
                    // Extract Hero Cover Image
                    if (($block['type'] ?? '') === 'hero' && !empty($block['cover_url'])) {
                        $coverUrl = $block['cover_url'];
                    }
                    // Extract Gallery images
                    if (($block['type'] ?? '') === 'gallery' && !empty($block['images']) && is_array($block['images'])) {
                        $galleryImages = array_merge($galleryImages, $block['images']);
                    }
                }
            }

            return [
                'id' => $item->id,
                'title' => $item->title,
                'slug' => $item->slug,
                'type' => $item->type,
                'preview_text' => Str_limit($previewText, 160),
                'cover_url' => $coverUrl,
                'gallery_images' => $galleryImages,
                'content_blocks' => $item->content_blocks, // Complete visual layouts
                'published_at' => $item->published_at ? $item->published_at->toIso8601String() : null,
                'created_at' => $item->created_at->toIso8601String(),
                'program' => $item->program ? [
                    'id' => $item->program->id,
                    'name' => $item->program->name,
                    'slug' => $item->program->slug,
                    'logo_url' => $item->program->logo_path ? asset('storage/' . $item->program->logo_path) : null,
                ] : null,
                'author' => $item->author ? [
                    'id' => $item->author->id,
                    'name' => $item->author->name,
                ] : null,
                'categories' => $item->categories->map(function ($cat) {
                    return [
                        'id' => $cat->id,
                        'name' => $cat->name,
                        'slug' => $cat->slug,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedItems,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ]);
    }
}

// Simple inline helper if Str_limit is undefined
function Str_limit($value, $limit = 100, $end = '...') {
    if (mb_strwidth($value, 'UTF-8') <= $limit) {
        return $value;
    }
    return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
}
