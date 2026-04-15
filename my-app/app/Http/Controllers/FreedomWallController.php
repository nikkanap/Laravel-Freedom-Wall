<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FreedomWallController extends Controller
{
    public function index()
    {
        $postsPerPage = 10;
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $postsPerPage;
        
        $posts = DB::table('posts as p')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->select(
                'p.id',
                'p.user_id',
                'p.content',
                'p.parent_id',
                'p.deleted',
                'u.username'
            )
            ->orderBy('p.id', 'desc')
            ->limit($postsPerPage)
            ->offset($offset)
            ->get();

        $topPosts = $posts->whereNull('parent_id');

        $replies = $posts
            ->whereNotNull('parent_id')
            ->groupBy('parent_id');

        $totalPosts = DB::table('posts')->count();
        $totalPages = ceil($totalPosts / $postsPerPage);

        return view('index', [
            'topPosts' => $topPosts,
            'replies' => $replies,
            'page' => $page,
            'totalPages' => $totalPages,

            // auth info
            'isLoggedIn' => Auth::check(),
            'sessionUserId' => Auth::id(),
            'username' => Auth::user()?->username,
        ]);
    }

    public function showRegister()
    {
        return view('register');
    }

    public function showLogin()
    {
        return view('login');
    }

    public function register(Request $request)
    {
        if (Auth::check()) {
            return redirect('/');
        }

        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:8'
        ]);

        try {
            DB::table('users')->insert([
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

    public function login(Request $request)
    {
        if (Auth::check()) {
            return redirect('/');
        }

        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        try {
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

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
