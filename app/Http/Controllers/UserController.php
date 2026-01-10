<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function assignRoles(User $user, Request $request): RedirectResponse
    {
        $roles = $request->input('roles', []);

        // Sync roles (remove old, add new)
        $user->syncRoles($roles);

        return redirect()
            ->back()
            ->with('success', "Roles successfully assigned to {$user->name}.");
    }
}
