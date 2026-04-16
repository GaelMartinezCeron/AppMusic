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

        // 🔹 Si no tiene historial → usuario nuevo
        $hasHistory = History::where('user_id', $user->id)->exists();

        if (!$hasHistory) {
            // Mostrar canciones generales
            $songs = Song::inRandomOrder()->limit(10)->get();
        } else {
            // Obtener géneros favoritos
            $favoriteGenres = History::where('user_id', $user->id)
                ->join('songs', 'histories.song_id', '=', 'songs.id')
                ->select('songs.genre_id', DB::raw('count(*) as total'))
                ->groupBy('songs.genre_id')
                ->orderByDesc('total')
                ->pluck('genre_id');

            $songs = Song::whereIn('genre_id', $favoriteGenres)
                ->inRandomOrder()
                ->limit(10)
                ->get();
        }

        return view('dashboard', compact('songs', 'user'));
    }
}