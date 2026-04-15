<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $request->validate([
            'message' => 'required|string',
            'parent_id' => 'nullable|integer'
        ]);

        DB::table('posts')->insert([
            'user_id' => Auth::id(),
            'content' => $request->message,
            'parent_id' => $request->parent_id,
        ]);

        return redirect('/');
    }
}