<?php

namespace App\Http\Controllers;

use App\Models\PhotoAlbum;
use App\Services\MediaServices\MediaService;
use Illuminate\Database\Eloquent\Collection;

class AlbumController extends Controller
{

    public function index(MediaService $mediaService)
    {
        $albums = PhotoAlbum::withCount('photos')
            ->orderBy('created_at', 'desc')
            ->get();

        $albums = $mediaService->addCoverToAlbums($albums);

        if ($albums->count()) {
            $cover = $albums->random()->cover;
        } else {
            $cover = null;
        }


        return view('albumlist', compact('albums', 'cover'));
    }

    public function photos(PhotoAlbum $album, MediaService $mediaService)
    {
        $games = $album->games()
            ->withCount(['album', 'updates', 'stats'])
            ->get();

        $album = $mediaService->addCoverToAlbums(new Collection([$album]))->first();

        return view('album', compact('album', 'games'));
    }
}
