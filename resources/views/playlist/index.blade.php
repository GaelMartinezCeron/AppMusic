<x-app-layout>

<h1 class="text-2xl mb-4">Tus Playlists</h1>

@foreach($playlists as $playlist)
    <a href="/playlist/{{ $playlist->id }}" 
       class="block bg-gray-800 p-4 mb-2 rounded hover:bg-gray-700">
        {{ $playlist->name }}
    </a>
@endforeach

</x-app-layout>