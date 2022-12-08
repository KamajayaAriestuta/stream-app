<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  

class MovieController extends Controller
{
    public function index()
    {
        return view('admin.movies');
    }

    public function create()
    {
        return view('admin.movie-create');
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');

        $request->validate([
            'title' => 'required|string',
            'small_thumbnail' => 'required|image|mimes:jpg, jpeg, png', 
            'large_thumbnail' => 'required|image|mimes:jpg, jpeg, png', 
            'trailer' => 'required|url',
            'movie' => 'required|url',
            'casts' => 'required|string',
            'categories' => 'required|string',
            'realese_date' => 'required|string',
            'about' => 'required|string',
            'short-about' => 'required|string',
            'duration' => 'required|string',
            'featured' => 'required'
        ]);
    }
} 
