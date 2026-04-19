<x-app-layout>

<div class="max-w-7xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-6">🎼 Mis Playlists</h1>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

        @foreach($playlists as $playlist)
            <div class="bg-white/5 p-4 rounded-xl hover:bg-white/10 transition">
                <h2 class="font-semibold">{{ $playlist->name }}</h2>
            </div>
        @endforeach

    </div>

</div>

</x-app-layout>