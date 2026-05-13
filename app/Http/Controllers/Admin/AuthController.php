<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return Auth::check() ? redirect()->route('admin.dashboard') : view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $stats = [
            'total_blogs'      => \App\Models\Blog::count(),
            'total_categories' => \App\Models\Category::count(),
            'recent_blogs'     => \App\Models\Blog::with('category')->latest()->take(5)->get(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}

public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    \Log::info('Login attempt', ['email' => $credentials['email']]);
    \Log::info('Auth check before', ['auth' => Auth::check()]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        \Log::info('Auth attempt succeeded');
        \Log::info('Auth check after', ['auth' => Auth::check()]);
        
        $request->session()->regenerate();
        
        \Log::info('Session id', ['id' => $request->session()->getId()]);
        \Log::info('Redirecting to', ['url' => route('admin.dashboard')]);
        
        return redirect()->route('admin.dashboard');
    }

    \Log::info('Auth attempt FAILED');
    return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
}
