<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Playlist;
use App\Models\Song;

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

    public function show($id)
    {
        $playlist = Playlist::with('songs')->findOrFail($id);
        $songs = Song::all();

        return view('playlist.show', compact('playlist', 'songs'));
    }

    public function addSong(Request $request)
    {
        $playlist = Playlist::findOrFail($request->playlist_id);

        $playlist->songs()->attach($request->song_id);

        return back();
    }
}