<?php

namespace App\Http\Controllers;

use App\Models\PhotoAlbum;
use Illuminate\Http\Request;

use App\Http\Requests;

class AlbumController extends Controller
{

    public function index()
    {
        $albums = PhotoAlbum::with('cover')
            ->withCount('photos')
            ->orderBy('created_at', 'desc')
            ->get();

        $albums = $albums->filter(function($album) {
           return $album->photos_count > 0;
        });

        if ($albums->count()) {
            $cover = $albums->random()->cover;
        } else {
            $cover = null;
        }


        return view('albumlist', compact('albums', 'cover'));
    }

    public function photos(PhotoAlbum $album)
    {
        $photos = $album->photos()->paginate(48);
        $games = $album->games()
            ->withCount(['album', 'updates', 'stats'])
            ->get();

        // dd($games);

        return view('album', compact('album', 'photos', 'games'));
    }
}
