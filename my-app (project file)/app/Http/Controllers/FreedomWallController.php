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

    public function register(Request $request) {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:8'
        ]);

        DB::table('users')->insertGetId([
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        return redirect('/login');
    }

}
