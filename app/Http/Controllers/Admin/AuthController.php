<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showLoginForm()
    {
        return view('admin-views.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|max:8',
        ], [
            'email.required' => 'email is required',
            'password.required' => 'password is required',
        ]);

        if ($validate->fails()) {
            // $this->toastAlert('error', $validate->errors()->first());
            return redirect()->back();
        }
        // Simple validation
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect based on role
            if ($user->hasRole('admin') || $user->hasRole('manager')) {
                return redirect()->intended('/admin/dashboard');
            } else {
                // User is not admin/manager
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Access denied. Admin privileges required.',
                ])->onlyInput('email');
            }
        }

        // If login fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
