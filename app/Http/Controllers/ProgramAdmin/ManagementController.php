<?php

namespace App\Http\Controllers\ProgramAdmin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Period;
use App\Models\Management;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManagementController extends Controller
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

    public function create(Request $request)
    {
        $program = $this->getProgram();
        $periodId = $request->query('period_id');
        $period = $program->periods()->findOrFail($periodId);

        return view('cms.managements.create', compact('period'));
    }

    public function store(Request $request)
    {
        $program = $this->getProgram();
        $request->validate([
            'period_id' => 'required|exists:periods,id',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'linkedin' => 'nullable|string|url|max:255',
            'instagram' => 'nullable|string|url|max:255',
            'order_no' => 'required|integer',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $period = $program->periods()->findOrFail($request->period_id);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('managements', 'public');
        }

        $period->managements()->create([
            'name' => $request->name,
            'position' => $request->position,
            'bio' => $request->bio,
            'linkedin' => $request->linkedin,
            'instagram' => $request->instagram,
            'order_no' => $request->order_no,
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('cms.periods.index')->with('status', 'Anggota pengurus baru berhasil ditambahkan.');
    }

    public function edit(Management $management)
    {
        $program = $this->getProgram();
        if ($management->period->program_id !== $program->id) {
            abort(403);
        }

        return view('cms.managements.edit', compact('management'));
    }

    public function update(Request $request, Management $management)
    {
        $program = $this->getProgram();
        if ($management->period->program_id !== $program->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'linkedin' => 'nullable|string|url|max:255',
            'instagram' => 'nullable|string|url|max:255',
            'order_no' => 'required|integer',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $photoPath = $management->photo_path;
        if ($request->hasFile('photo')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('managements', 'public');
        }

        $management->update([
            'name' => $request->name,
            'position' => $request->position,
            'bio' => $request->bio,
            'linkedin' => $request->linkedin,
            'instagram' => $request->instagram,
            'order_no' => $request->order_no,
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('cms.periods.index')->with('status', 'Detail anggota pengurus berhasil diperbarui.');
    }

    public function destroy(Management $management)
    {
        $program = $this->getProgram();
        if ($management->period->program_id !== $program->id) {
            abort(403);
        }

        if ($management->photo_path) {
            Storage::disk('public')->delete($management->photo_path);
        }

        $management->delete();
        return redirect()->route('cms.periods.index')->with('status', 'Anggota pengurus berhasil dihapus.');
    }
}
