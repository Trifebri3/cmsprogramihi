<?php

namespace App\Http\Controllers\ProgramAdmin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
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
        $periods = $program->periods()->get();
        return view('cms.periods.index', compact('program', 'periods'));
    }

    public function create()
    {
        return view('cms.periods.create');
    }

    public function store(Request $request)
    {
        $program = $this->getProgram();

        $request->validate([
            'name' => 'required|string|max:255',
            'year_start' => 'required|integer|min:2000|max:2100',
            'year_end' => 'required|integer|min:2000|max:2100|gte:year_start',
            'status' => 'required|string|in:active,archived',
        ]);

        if ($request->status === 'active') {
            $program->periods()->update(['status' => 'archived']);
        }

        $program->periods()->create([
            'name' => $request->name,
            'year_start' => $request->year_start,
            'year_end' => $request->year_end,
            'status' => $request->status,
        ]);

        return redirect()->route('cms.periods.index')->with('status', 'Periode baru berhasil ditambahkan.');
    }

    public function edit(Period $period)
    {
        $program = $this->getProgram();
        if ($period->program_id !== $program->id) {
            abort(403);
        }
        return view('cms.periods.edit', compact('period'));
    }

    public function update(Request $request, Period $period)
    {
        $program = $this->getProgram();
        if ($period->program_id !== $program->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'year_start' => 'required|integer|min:2000|max:2100',
            'year_end' => 'required|integer|min:2000|max:2100|gte:year_start',
            'status' => 'required|string|in:active,archived',
        ]);

        if ($request->status === 'active') {
            $program->periods()->where('id', '!=', $period->id)->update(['status' => 'archived']);
        }

        $period->update([
            'name' => $request->name,
            'year_start' => $request->year_start,
            'year_end' => $request->year_end,
            'status' => $request->status,
        ]);

        return redirect()->route('cms.periods.index')->with('status', 'Periode kepengurusan berhasil diperbarui.');
    }

    public function destroy(Period $period)
    {
        $program = $this->getProgram();
        if ($period->program_id !== $program->id) {
            abort(403);
        }

        $period->delete();
        return redirect()->route('cms.periods.index')->with('status', 'Periode kepengurusan berhasil dihapus.');
    }
}
