<?php

namespace App\Http\Controllers\ProgramAdmin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    protected function getProgram()
    {
        $user = auth()->user();
        $programId = $user->hasRole('super-admin') ? session('active_program_id') : $user->program_id;
        if (!$programId) {
            if ($user->hasRole('super-admin')) {
                $firstProgram = \App\Models\Program::first();
                if ($firstProgram) {
                    session(['active_program_id' => $firstProgram->id]);
                    return $firstProgram;
                }
            }
            abort(403, 'Program context not resolved.');
        }
        return Program::findOrFail($programId);
    }

    public function index()
    {
        $program = $this->getProgram();
        $documents = $program->documents()->get();
        return view('cms.documents.index', compact('program', 'documents'));
    }

    public function create()
    {
        return view('cms.documents.create');
    }

    public function store(Request $request)
    {
        $program = $this->getProgram();
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|in:public,internal,archive',
            'status' => 'required|string|in:active,inactive',
            'document_file' => 'required|file|mimes:pdf,docx,xlsx|max:10240', // Max 10MB
        ]);

        $filePath = null;
        if ($request->hasFile('document_file')) {
            $filePath = $request->file('document_file')->store('documents', 'public');
        }

        $program->documents()->create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'status' => $request->status,
            'file_path' => $filePath,
        ]);

        return redirect()->route('cms.documents.index')->with('status', 'Berkas dokumen baru berhasil diunggah.');
    }

    public function edit(Document $document)
    {
        $program = $this->getProgram();
        if ($document->program_id !== $program->id) {
            abort(403);
        }

        return view('cms.documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $program = $this->getProgram();
        if ($document->program_id !== $program->id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|in:public,internal,archive',
            'status' => 'required|string|in:active,inactive',
            'document_file' => 'nullable|file|mimes:pdf,docx,xlsx|max:10240', // Max 10MB
        ]);

        $filePath = $document->file_path;
        if ($request->hasFile('document_file')) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('document_file')->store('documents', 'public');
        }

        $document->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'status' => $request->status,
            'file_path' => $filePath,
        ]);

        return redirect()->route('cms.documents.index')->with('status', 'Berkas dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        $program = $this->getProgram();
        if ($document->program_id !== $program->id) {
            abort(403);
        }

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();
        return redirect()->route('cms.documents.index')->with('status', 'Berkas dokumen berhasil dihapus.');
    }
}
