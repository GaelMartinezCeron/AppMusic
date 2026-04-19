<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\History;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $hasHistory = History::where('user_id', $user->id)->exists();

        if (!$hasHistory) {
            $songs = Song::inRandomOrder()->limit(10)->get();
        } else {
            $favoriteGenres = History::where('histories.user_id', $user->id)
                ->join('songs', 'histories.song_id', '=', 'songs.id')
                ->select('songs.genre_id', DB::raw('count(*) as total'))
                ->groupBy('songs.genre_id')
                ->orderByDesc('total')
                ->pluck('songs.genre_id');

            $songs = Song::whereIn('genre_id', $favoriteGenres)
                ->inRandomOrder()
                ->limit(10)
                ->get();
        }

        $songsData = $songs->map(function ($song) {
            return [
                'id' => $song->id,
                'title' => $song->title,
                'artist' => $song->artist,
                'file_path' => $song->file_path,
                'image' => $song->image
            ];
        });

        return view('dashboard', compact('songs', 'songsData'));
    }

    public function play($id)
    {
        $song = Song::find($id);

        if (!$song) {
            return response()->json(['error' => 'Canción no encontrada'], 404);
        }

        History::create([
            'user_id' => auth()->id(),
            'song_id' => $song->id
        ]);

        return response()->json([
            'id' => $song->id,
            'title' => $song->title,
            'artist' => $song->artist,
            'file_path' => $song->file_path,
            'image' => $song->image
        ]);
    }

    public function dj()
    {
        $user = auth()->user();

        $hasHistory = History::where('user_id', $user->id)->exists();

        if (!$hasHistory) {
            // Sin historial: orden aleatorio
            $songs = Song::inRandomOrder()->get();
        } else {
            $genres = History::where('histories.user_id', $user->id)
                ->join('songs', 'histories.song_id', '=', 'songs.id')
                ->select('songs.genre_id', DB::raw('count(*) as total'))
                ->groupBy('songs.genre_id')
                ->orderByDesc('total')
                ->pluck('songs.genre_id');

            if ($genres->isEmpty()) {
                $songs = Song::inRandomOrder()->get();
            } else {
                 $genreList = $genres->implode(',');
                $songs = Song::orderByRaw("FIELD(genre_id, {$genreList}) DESC")
                    ->get();
            }
        }

        return view('dj', [
            'songsData' => $songs
        ]);
    }
}