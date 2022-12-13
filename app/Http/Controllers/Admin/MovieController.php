<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Movie;
use Illuminate\Support\Facades\Storage;
  

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movies', ['movies' => $movies]);
    }

    public function create()
    {
        return view('admin.movie-create');
    }
    public function edit($id)
    {
        $movie = Movie::find($id);
        return view('admin.movie-edit', ['movie' => $movie]);
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');

        $request->validate([
            'title' => 'required|string',
            'small_thumbnail' => 'required|image|mimes:jpg,jpeg,png', 
            'large_thumbnail' => 'required|image|mimes:jpg,jpeg,png', 
            'trailer' => 'required|url',
            'movie' => 'required|url',
            'casts' => 'required|string',
            'categories' => 'required|string',
            'release_date' => 'required|string',
            'about' => 'required|string',
            'short_about' => 'required|string',
            'duration' => 'required|string',
            'featured' => 'required'
        ]);

        $smallThumbnail = $request->small_thumbnail;
        $largeThumbnail = $request->large_thumbnail;

        $originalSmallThumbnailName= Str::random(10).$smallThumbnail->getClientOriginalName();
        $originalLargeThumbnailName= Str::random(10).$largeThumbnail->getClientOriginalName();

        $smallThumbnail->storeAs('public/thumbnail', $originalSmallThumbnailName);
        $largeThumbnail->storeAs('public/thumbnail', $originalLargeThumbnailName);

        $data['small_thumbnail'] = $originalSmallThumbnailName;
        $data['large_thumbnail'] = $originalLargeThumbnailName;

        Movie::create($data);

        return redirect()->route('admin.movies')->with('success', 'success detected');
    }
    public function update(Request $request, $id){
        $data = $request->except('_token');
        $request->validate([
            'title' => 'required|string',
            'small_thumbnail' => 'image|mimes:jpg,jpeg,png', 
            'large_thumbnail' => 'image|mimes:jpg,jpeg,png', 
            'trailer' => 'required|url',
            'movie' => 'required|url',
            'casts' => 'required|string',
            'categories' => 'required|string',
            'release_date' => 'required|string',
            'about' => 'required|string',
            'short_about' => 'required|string',
            'duration' => 'required|string',
            'featured' => 'required'
        ]);

        $movie = Movie::find($id);
    
        if ($request->small_thumbnail) 
        {
            //save new small thumbnail
            $smallThumbnail = $request->small_thumbnail;
            $originalSmallThumbnailName= Str::random(10).$smallThumbnail->getClientOriginalName();
            $smallThumbnail->storeAs('public/thumbnail', $originalSmallThumbnailName);
            $data['small_thumbnail'] = $originalSmallThumbnailName;

            //delete old small thumbnail
            Storage::delete('public/thumbnail/'.$movie->small_thumbnail);
        }
        if ($request->large_thumbnail)
        {
            //save new small thumbnail
            $largeThumbnail = $request->large_thumbnail;
            $originalLargeThumbnailName= Str::random(10).$largeThumbnail->getClientOriginalName();
            $largeThumbnail->storeAs('public/thumbnail', $originalLargeThumbnailName);
            $data['large_thumbnail'] = $originalLargeThumbnailName;

            //delete old small thumbnail
            Storage::delete('public/thumbnail/'.$movie->large_thumbnail);
        }

        $movie->update($data);
        return redirect()->route('admin.movies')->with('success', 'sucess updated');
    }
}
