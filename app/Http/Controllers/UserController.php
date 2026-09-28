<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->withCount('activeIssueRecords')
            ->orderByDesc('is_admin')
            ->orderBy('name')
            ->get();

        return view('users.index', [
            'users' => $users,
            'admins' => $users->where('is_admin', true)->count(),
            'members' => $users->where('is_admin', false)->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            // The User model's `password` cast hashes this value for us.
            'password' => $data['password'],
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return back()->with('success', "{$data['name']} was added.");
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->isAdmin(), 422, 'An admin cannot be deleted.');
        abort_if(auth()->id() === $user->id, 422, 'You cannot delete yourself.');
        // Issue records cascade on delete, so this would erase the user's history — block instead.
        abort_if($user->issueRecords()->exists(), 422, 'This user has issue history — they cannot be deleted.');

        $user->delete();

        return back()->with('success', 'The user was deleted.');
    }
}
