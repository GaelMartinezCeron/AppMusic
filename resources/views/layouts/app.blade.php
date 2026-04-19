<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Music App</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-black text-white font-sans">

<div class="flex h-screen">

    <!-- 🔥 SIDEBAR -->
    <aside id="sidebar" class="w-64 min-h-screen bg-gradient-to-b from-gray-900 to-black p-5 shadow-xl transition-all duration-300">

        <button onclick="toggleSidebar()" class="mb-4 text-gray-400 hover:text-white">
            ☰
        </button>

        <h1 class="text-xl font-bold text-purple-400 mb-6">🎧 MusicApp</h1>

        <nav class="space-y-2">
            <a href="/dashboard" class="block p-2 rounded hover:bg-white/10 transition">Inicio</a>
            <a href="/playlist" class="block p-2 rounded hover:bg-white/10 transition">Playlists</a>
            <a href="/dj" class="block p-2 rounded hover:bg-white/10 transition">DJ Live</a>
        </nav>

        <hr class="my-4 border-white/10">

        <button onclick="openPlaylistModal()" 
            class="w-full bg-purple-600 hover:bg-purple-500 p-2 rounded transition">
            + Crear Playlist
        </button>

    </aside>

    <!-- 🔥 CONTENIDO -->
    <div class="flex-1 flex flex-col">

        <!-- HEADER -->
        <header class="bg-black/70 backdrop-blur px-6 py-3 flex justify-between items-center border-b border-white/10">
            <span class="text-sm text-gray-300">
                Hola, {{ auth()->user()->name }}
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-red-400 hover:text-red-300 transition">
                    Salir
                </button>
            </form>
        </header>

        <!-- MAIN -->
        <main class="flex-1 overflow-y-auto bg-gradient-to-b from-black via-gray-900 to-black">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {{ $slot }}
            </div>

        </main>

    </div>
</div>

<!-- 🎧 MODAL PLAYLIST -->
<div id="playlistModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">

    <div class="bg-gray-900 p-6 rounded-lg w-96 shadow-2xl border border-white/10">
        <h2 class="text-lg mb-4 font-semibold">Nueva Playlist</h2>

        <form method="POST" action="/playlist">
            @csrf
            <input type="text" name="name" placeholder="Nombre"
                class="w-full p-2 mb-3 bg-black border border-gray-700 rounded focus:outline-none focus:border-purple-500">

            <button class="w-full bg-purple-600 hover:bg-purple-500 p-2 rounded transition">
                Guardar
            </button>
        </form>

        <button onclick="closePlaylistModal()" class="mt-3 text-gray-400 hover:text-white">
            Cancelar
        </button>
    </div>
</div>

<!-- 🔥 SCRIPTS -->
<script>
function toggleSidebar(){
    let s = document.getElementById('sidebar');
    s.classList.toggle('w-64');
    s.classList.toggle('w-20');
}

function openPlaylistModal(){
    let modal = document.getElementById('playlistModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closePlaylistModal(){
    let modal = document.getElementById('playlistModal');
    modal.classList.add('hidden');
}
</script>

</body>
</html>