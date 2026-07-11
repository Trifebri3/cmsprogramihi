<?php

namespace App\Http\Controllers\ProgramAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Get the active program context for the logged in user.
     */
    protected function getProgramId()
    {
        $user = auth()->user();
        if ($user->hasRole('super-admin')) {
            return session('active_program_id');
        }
        return $user->program_id;
    }

    public function index()
    {
        $programId = $this->getProgramId();
        
        if (auth()->user()->hasRole('super-admin')) {
            $users = User::with('program', 'roles')->get();
        } else {
            $users = User::where('program_id', $programId)->with('roles')->get();
        }

        return view('program.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'super-admin')->get();
        return view('program.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $programId = $this->getProgramId();
        if (!$programId && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Context program not defined.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        // Security check: Program Admin cannot assign super-admin role
        if ($request->role === 'super-admin' && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized role assignment.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'program_id' => $programId,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('cms.users.index')->with('status', 'User created successfully under your program profile.');
    }

    public function edit($id)
    {
        $userToEdit = User::findOrFail($id);
        $programId = $this->getProgramId();

        // Security check: Program Admin cannot edit users from other programs
        if (!auth()->user()->hasRole('super-admin') && $userToEdit->program_id !== $programId) {
            abort(403, 'Unauthorized program profile access.');
        }

        $roles = Role::where('name', '!=', 'super-admin')->get();
        return view('program.users.edit', compact('userToEdit', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $userToEdit = User::findOrFail($id);
        $programId = $this->getProgramId();

        // Security check: Program Admin cannot edit users from other programs
        if (!auth()->user()->hasRole('super-admin') && $userToEdit->program_id !== $programId) {
            abort(403, 'Unauthorized program profile access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        // Security check: Program Admin cannot assign super-admin role
        if ($request->role === 'super-admin' && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized role assignment.');
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $userToEdit->update($userData);
        $userToEdit->syncRoles([$request->role]);

        return redirect()->route('cms.users.index')->with('status', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $userToDelete = User::findOrFail($id);
        $programId = $this->getProgramId();

        // Security check: Program Admin cannot delete users from other programs
        if (!auth()->user()->hasRole('super-admin') && $userToDelete->program_id !== $programId) {
            abort(403, 'Unauthorized program profile access.');
        }

        $userToDelete->delete();
        return redirect()->route('cms.users.index')->with('status', 'User deleted successfully.');
    }
}
