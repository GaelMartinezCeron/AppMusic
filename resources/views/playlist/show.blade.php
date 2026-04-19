<x-app-layout>

<h1 class="text-2xl font-bold mb-6">{{ $playlist->name }}</h1>

<!-- AGREGAR CANCIONES -->
<form method="POST" action="/playlist/add-song" class="mb-6">
    @csrf
    <input type="hidden" name="playlist_id" value="{{ $playlist->id }}">

    <div class="flex gap-2">
        <select name="song_id" class="bg-gray-800 text-white p-2 rounded w-full">
            @foreach($songs as $song)
                <option value="{{ $song->id }}">{{ $song->title }} - {{ $song->artist }}</option>
            @endforeach
        </select>

        <button class="bg-purple-600 px-4 rounded hover:bg-purple-500">
            Agregar
        </button>
    </div>
</form>

<!-- GRID DE CANCIONES -->
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-5">

@foreach($playlist->songs as $song)
<div class="group bg-gray-900 rounded-lg overflow-hidden shadow cursor-pointer"
     onclick="playSong({{ $song->id }})">

    <!-- IMAGEN -->
    <div class="relative">
        <img src="{{ asset('storage/' . $song->image) }}"
             class="w-full h-40 object-cover group-hover:scale-105 transition">

        <!-- BOTÓN PLAY -->
        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
            <div class="bg-purple-600 w-12 h-12 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.633 7.633a9 9 0 010 12.734m0-12.734c-1.286 1.286-1.286 3.355 0 4.641m0-4.641a3 3 0 014.241 0m0 0c1.286 1.286 1.286 3.355 0 4.641m0-4.641a3 3 0 014.241 0m0 0c1.286 1.286 1.286 3.355 0 4.641M14.241 17.241a3 3 0 014.241 0" />
                </svg> 
            </div>
        </div>
    </div>

    <!-- INFO -->
    <div class="p-3">
        <h3 class="text-white text-sm font-semibold truncate">{{ $song->title }}</h3>
        <p class="text-gray-400 text-xs truncate">{{ $song->artist }}</p>
    </div>

</div>
@endforeach

</div>

<!-- REPRODUCTOR -->
<div class="mt-8">
    <audio id="player" controls class="w-full"></audio>
</div>

<script>
function playSong(id){
    fetch('/play/' + id)
        .then(res => res.json())
        .then(data => {

            if(data.error){
                alert(data.error);
                return;
            }

            let player = document.getElementById('player');

            player.src = '/storage/' + data.file_path;
            player.load();
            player.play();
        })
        .catch(err => console.error(err));
}
</script>

</x-app-layout>