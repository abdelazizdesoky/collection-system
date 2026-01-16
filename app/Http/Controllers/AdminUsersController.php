<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class AdminUsersController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles', 'collector')->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if (! empty($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user): View
    {
        $user->load('roles', 'collector');

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $roles = Role::all();
        $user->load('roles');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'string',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Export users to Excel.
     */
    public function export()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return Excel::download(new UsersExport, 'users_'.now()->format('Y-m-d_His').'.xlsx');
    }
}
