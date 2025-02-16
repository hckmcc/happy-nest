<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function showUsers()
    {
        $users = User::all();
        return view('admin.users.users', compact('users'));
    }

    public function showUserPage(User $user)
    {
        $user = User::query()
            ->withCount(['ads', 'reviews'])
            ->withAvg('reviews', 'rate')
            ->findOrFail($user->id);

        $reviews = $user->reviews()
            ->with('buyer')
            ->latest()
            ->get();

        $roles = Role::all();

        //$user->load('roles');
        return view('admin.users.userPage', compact('user', 'roles', 'reviews'));
    }
    public function showUserAddPage()
    {
        $roles = Role::all();
        return view('admin.users.createUser', compact('roles'));
    }

    public function deleteUser(User $user)
    {
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'Пользователь успешно удален');
    }
    public function updateUserRoles(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id'
        ]);

        // Синхронизируем роли (удаляем старые и добавляем новые)
        $user->roles()->sync($request->roles ?? [2]);

        return back()->with('success', 'Роли пользователя успешно обновлены');
    }
}
