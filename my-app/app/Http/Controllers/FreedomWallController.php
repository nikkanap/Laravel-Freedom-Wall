<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FreedomWallController extends Controller
{
    public function index() {
        $topPosts = [];
        $replies = [];
        $page = 1;
        $totalPages = 1;

        return view('index', [
            'topPosts' => $topPosts,
            'replies' => $replies,
            'page' => $page,
            'totalPages' => $totalPages,
            'isLoggedIn' => Auth::check(), 
            'sessionUserId' => Auth::id(),
            'username' => Auth::user()?->username,
        ]);
    }

    public function showRegister() {
        return view('register');
    }

    public function showLogin() {
        return view('login');
    }

    public function register(Request $request) {
        if(Auth::check()) {
            return redirect('/');
        }

        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:8'
        ]);

        try {
            DB::table('users')->insertGetId([
                'username' => $request->username,
                'password' => Hash::make($request->password)
            ]);

            return redirect('/login');
        } catch (\Exception $e) {
            return back()->withErrors([
                'register' => 'Something went wrong. Please try again.'
            ])->withInput();
        }
    }

    public function login(Request $request) {
        if(Auth::check()) {
            return redirect('/');
        }

        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        try{
            $user = DB::table('users')
            ->where('username', $request->username)
            ->first();

            if ($user && Hash::check($request->password, $user->password)) {
                Auth::loginUsingId($user->id);
                return redirect('/');
            }

            return back()->withErrors([
                'login' => 'Invalid username or password.'
            ])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors([
                'login' => 'Something went wrong. Please try again.'
            ])->withInput();
        }
    }

    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
}
