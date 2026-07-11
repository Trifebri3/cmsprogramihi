<?php

namespace App\Http\Controllers\ProgramAdmin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Album;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
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
        $albums = $program->albums()->withCount('photos')->get();
        return view('cms.albums.index', compact('program', 'albums'));
    }

    public function create()
    {
        return view('cms.albums.create');
    }

    public function store(Request $request)
    {
        $program = $this->getProgram();
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $program->albums()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('cms.albums.index')->with('status', 'Album galeri baru berhasil dibuat.');
    }

    public function show(Album $album)
    {
        $program = $this->getProgram();
        if ($album->program_id !== $program->id) {
            abort(403);
        }

        $photos = $album->photos()->get();
        return view('cms.albums.show', compact('album', 'photos'));
    }

    public function uploadPhoto(Request $request, Album $album)
    {
        $program = $this->getProgram();
        if ($album->program_id !== $program->id) {
            abort(403);
        }

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:3072',
            'caption' => 'nullable|string|max:255',
        ]);

        $path = $request->file('photo')->store('photos', 'public');

        $album->photos()->create([
            'file_path' => $path,
            'caption' => $request->caption,
        ]);

        return redirect()->route('cms.albums.show', $album->id)->with('status', 'Foto berhasil diunggah dan ditambahkan ke album.');
    }

    public function deletePhoto(Photo $photo)
    {
        $program = $this->getProgram();
        if ($photo->album->program_id !== $program->id) {
            abort(403);
        }

        Storage::disk('public')->delete($photo->file_path);
        $photo->delete();

        return redirect()->route('cms.albums.show', $photo->album_id)->with('status', 'Foto berhasil dihapus.');
    }

    public function destroy(Album $album)
    {
        $program = $this->getProgram();
        if ($album->program_id !== $program->id) {
            abort(403);
        }

        foreach ($album->photos as $photo) {
            Storage::disk('public')->delete($photo->file_path);
            $photo->delete();
        }

        $album->delete();
        return redirect()->route('cms.albums.index')->with('status', 'Album galeri berhasil dihapus beserta semua fotonya.');
    }
}
