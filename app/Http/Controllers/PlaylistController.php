<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Playlist;

class PlaylistController extends Controller
{
    public function index()
    {
        $playlists = Playlist::where('user_id', auth()->id())->get();

        return view('playlist.index', compact('playlists'));
    }

    public function store(Request $request)
    {
        Playlist::create([
            'name' => $request->name,
            'user_id' => auth()->id()
        ]);

        return redirect('/playlist');
    }
}