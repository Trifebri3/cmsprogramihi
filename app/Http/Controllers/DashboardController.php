<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\User;
use App\Models\Content;
use App\Models\Document;
use App\Models\Album;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard based on their role.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('super-admin')) {
            // Calculate Super Admin global stats
            $totalPrograms = Program::count();
            $totalUsers = User::count();
            $totalDocuments = Document::count();
            $totalContents = Content::count();
            
            $programs = Program::all();
            
            // Current program context for Super Admin (stored in session)
            $activeProgramId = session('active_program_id');
            $activeProgram = null;
            if ($activeProgramId) {
                $activeProgram = Program::find($activeProgramId);
            }
            if (!$activeProgram && $programs->count() > 0) {
                $activeProgram = $programs->first();
                session(['active_program_id' => $activeProgram->id]);
            }

            return view('admin.dashboard', compact(
                'totalPrograms', 
                'totalUsers', 
                'totalDocuments', 
                'totalContents', 
                'programs', 
                'activeProgram'
            ));
        }

        // Program Admin, Editor, Contributor logic
        $programId = $user->program_id;
        if (!$programId) {
            abort(403, 'You are not assigned to any program.');
        }

        $program = Program::findOrFail($programId);
        
        // Calculate Program-specific stats
        $totalPosts = $program->contents()->where('type', 'post')->count();
        $totalPages = $program->contents()->where('type', 'page')->count();
        $totalAnnouncements = $program->contents()->where('type', 'announcement')->count();
        $totalEvents = $program->contents()->where('type', 'event')->count();
        $totalAlbums = $program->albums()->count();
        $totalDocs = $program->documents()->count();

        return view('program.dashboard', compact(
            'program', 
            'totalPosts', 
            'totalPages', 
            'totalAnnouncements', 
            'totalEvents', 
            'totalAlbums', 
            'totalDocs'
        ));
    }

    /**
     * Switch context of active program for Super Admin.
     */
    public function selectProgram(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
        ]);

        if (!auth()->user()->hasRole('super-admin')) {
            abort(403);
        }

        session(['active_program_id' => $request->program_id]);

        return back()->with('status', 'Switched to program context successfully.');
    }
}
