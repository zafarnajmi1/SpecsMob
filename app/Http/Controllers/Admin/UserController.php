<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('admin-views.users.list', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin-views.users.add', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = Str::slug($request->name) . '-' . rand(100, 999);
        $user->password = Hash::make($request->password);
        $user->status = 'active';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('users', 'public');
            $user->image = $path;
        }

        $user->save();
        $user->assignRole($request->role);

        ToastMagic::success('User created successfully');
        return redirect()->route('admin.users.index');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin-views.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $path = $request->file('image')->store('users', 'public');
            $user->image = $path;
        }

        $user->save();
        $user->syncRoles([$request->role]);

        ToastMagic::success('User updated successfully');
        return redirect()->route('admin.users.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            ToastMagic::error('You cannot delete yourself!');
            return back();
        }

        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();
        ToastMagic::success('User deleted successfully');
        return back();
    }

    // Profile Editing
    public function profile()
    {
        $user = auth()->user();
        return view('admin-views.profile.edit', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $path = $request->file('image')->store('users', 'public');
            $user->image = $path;
        }

        $user->save();

        ToastMagic::success('Profile updated successfully');
        return back();
    }
}
