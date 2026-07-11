<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Content;
use App\Models\Period;
use App\Models\Album;
use App\Models\Document;
use Illuminate\Http\Request;

class ProgramPublicController extends Controller
{
    protected function getProgram()
    {
        if (!app()->bound(Program::class)) {
            abort(404, 'Program context not resolved.');
        }
        return app(Program::class);
    }

    public function home()
    {
        $program = $this->getProgram();
        
        $posts = $program->contents()
            ->where('type', 'post')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $announcements = $program->contents()
            ->where('type', 'announcement')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $events = $program->contents()
            ->where('type', 'event')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $activePeriod = $program->activePeriod()->first();
        $albums = $program->albums()->take(4)->get();

        return view('program.home', compact('program', 'posts', 'announcements', 'events', 'activePeriod', 'albums'));
    }

    public function management(Request $request)
    {
        $program = $this->getProgram();
        
        $periods = $program->periods()->get();
        $selectedPeriodId = $request->input('period_id');
        
        if ($selectedPeriodId) {
            $currentPeriod = $program->periods()->where('id', $selectedPeriodId)->firstOrFail();
        } else {
            $currentPeriod = $program->activePeriod()->first() ?? $periods->first();
        }

        $managements = $currentPeriod ? $currentPeriod->managements()->get() : collect();

        return view('program.management', compact('program', 'periods', 'currentPeriod', 'managements'));
    }

    public function gallery()
    {
        $program = $this->getProgram();
        $albums = $program->albums()->with('photos')->get();

        return view('program.gallery', compact('program', 'albums'));
    }

    public function documents()
    {
        $program = $this->getProgram();
        
        $documents = $program->documents()
            ->where('status', 'active')
            ->where('category', 'public')
            ->get();

        return view('program.documents', compact('program', 'documents'));
    }

    public function news(Request $request)
    {
        $program = $this->getProgram();
        $posts = $program->contents()
            ->where('type', 'post')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('program.news', [
            'program' => $program,
            'posts' => $posts,
            'pageTitle' => 'Kabar & Artikel Terbaru',
            'seoDescription' => 'Kumpulan berita, rilis warta, dan artikel dari program ' . $program->name,
            'seoKeywords' => 'berita, kabar, artikel, ' . $program->name
        ]);
    }

    public function page($arg1, $arg2 = null)
    {
        $slug = $arg2 ?? $arg1;
        $program = $this->getProgram();
        $content = $program->contents()
            ->where('type', 'page')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('program.content-show', [
            'program' => $program,
            'content' => $content,
            'pageTitle' => $content->seo_title ?: $content->title,
            'seoDescription' => $content->seo_description ?: $content->title,
            'seoKeywords' => $content->seo_keywords
        ]);
    }

    public function post($arg1, $arg2 = null)
    {
        $slug = $arg2 ?? $arg1;
        $program = $this->getProgram();
        $content = $program->contents()
            ->where('type', 'post')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('program.content-show', [
            'program' => $program,
            'content' => $content,
            'pageTitle' => $content->seo_title ?: $content->title,
            'seoDescription' => $content->seo_description ?: $content->title,
            'seoKeywords' => $content->seo_keywords
        ]);
    }

    public function announcement($arg1, $arg2 = null)
    {
        $slug = $arg2 ?? $arg1;
        $program = $this->getProgram();
        $content = $program->contents()
            ->where('type', 'announcement')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('program.content-show', [
            'program' => $program,
            'content' => $content,
            'pageTitle' => $content->seo_title ?: $content->title,
            'seoDescription' => $content->seo_description ?: $content->title,
            'seoKeywords' => $content->seo_keywords
        ]);
    }

    public function event($arg1, $arg2 = null)
    {
        $slug = $arg2 ?? $arg1;
        $program = $this->getProgram();
        $content = $program->contents()
            ->where('type', 'event')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('program.content-show', [
            'program' => $program,
            'content' => $content,
            'pageTitle' => $content->seo_title ?: $content->title,
            'seoDescription' => $content->seo_description ?: $content->title,
            'seoKeywords' => $content->seo_keywords
        ]);
    }

    public function category($arg1, $arg2 = null)
    {
        $slug = $arg2 ?? $arg1;
        $program = $this->getProgram();
        $category = $program->categories()->where('slug', $slug)->firstOrFail();
        
        $posts = $category->contents()
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->get();

        return view('program.category-show', compact('program', 'category', 'posts'));
    }
}
