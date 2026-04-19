{{-- resources/views/components/app-layout.blade.php --}}
@props(['title' => 'Music App'])

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-black text-white font-sans">

<div class="flex h-screen overflow-hidden">

    {{-- OVERLAY móvil --}}
    <div id="overlay"
         class="fixed inset-0 bg-black/60 z-20 hidden lg:hidden"
         onclick="closeSidebar()">
    </div>

    {{-- SIDEBAR --}}
    {{-- ✅ En móvil: fixed y fuera de pantalla. En desktop: relative y visible --}}
    <aside id="sidebar"
           class="fixed lg:static inset-y-0 left-0 z-30
                  w-64 h-full
                  bg-gradient-to-b from-gray-900 to-black
                  shadow-xl flex flex-col
                  transition-transform duration-300 ease-in-out
                  -translate-x-full lg:translate-x-0">

        {{-- Logo --}}
        <div class="flex items-center justify-between p-5 shrink-0">
            <h1 class="text-xl font-bold text-purple-400">🎧 MusicApp</h1>
            <button onclick="closeSidebar()" class="lg:hidden text-gray-400 hover:text-white text-xl leading-none">✕</button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
            <a href="/dashboard"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/10 transition
                      {{ request()->is('dashboard') || request()->is('/') ? 'bg-white/10 text-purple-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                <span>Inicio</span>
            </a>

            <a href="/playlist"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/10 transition
                      {{ request()->is('playlist*') ? 'bg-white/10 text-purple-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M15 6H3v2h12V6zm0 4H3v2h12v-2zM3 16h8v-2H3v2zM17 6v8.18c-.31-.11-.65-.18-1-.18-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3V8h3V6h-5z"/>
                </svg>
                <span>Playlists</span>
            </a>

            <a href="/dj"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/10 transition
                      {{ request()->is('dj') ? 'bg-white/10 text-purple-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                </svg>
                <span>DJ Live</span>
            </a>
        </nav>

        <hr class="mx-4 border-white/10">

        {{-- Crear playlist --}}
        <div class="p-4 shrink-0">
            <button onclick="openPlaylistModal()"
                    class="w-full flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-500 p-2.5 rounded-lg transition text-sm font-medium">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                </svg>
                Crear Playlist
            </button>
        </div>

        {{-- Usuario + logout --}}
        <div class="p-4 border-t border-white/10 shrink-0">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 min-w-0">
                    <img src="https://ui-avatars.com/api/?background=8b5cf6&color=fff&name={{ auth()->user()->name }}"
                         class="w-8 h-8 rounded-full shrink-0">
                    <span class="text-sm text-gray-300 truncate">{{ auth()->user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-400 hover:text-red-300 transition text-xs ml-2 shrink-0">
                        Salir
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- CONTENIDO PRINCIPAL --}}
    {{-- ✅ Siempre ocupa todo el ancho en móvil, comparte espacio en desktop --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden w-full">

        {{-- HEADER --}}
        <header class="bg-black/70 backdrop-blur px-4 sm:px-6 py-3
                       flex items-center justify-between
                       border-b border-white/10 shrink-0">

            {{-- Hamburguesa solo en móvil --}}
            <button onclick="openSidebar()"
                    class="lg:hidden p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <span class="text-sm font-medium text-gray-300">
                @if(request()->is('dj'))
                    🎧 DJ Live
                @elseif(request()->is('playlist*'))
                    🎵 Playlists
                @else
                    🏠 Inicio
                @endif
            </span>

            {{-- Espacio balanceador en móvil --}}
            <div class="w-10 lg:hidden"></div>

            {{-- En desktop mostrar nombre de usuario --}}
            <span class="hidden lg:block text-sm text-gray-400">{{ auth()->user()->name }}</span>
        </header>

        {{-- MAIN --}}
        <main class="flex-1 overflow-y-auto bg-gradient-to-b from-black via-gray-900 to-black">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot }}
            </div>
        </main>
    </div>
</div>

{{-- MODAL --}}
<div id="playlistModal"
     class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50 p-4">
    <div class="bg-gray-900 p-6 rounded-xl w-full max-w-sm shadow-2xl border border-white/10">
        <h2 class="text-lg mb-4 font-semibold">Nueva Playlist</h2>
        <form method="POST" action="/playlist">
            @csrf
            <input type="text" name="name" placeholder="Nombre de la playlist"
                   class="w-full p-3 mb-4 bg-black border border-gray-700 rounded-lg
                          focus:outline-none focus:border-purple-500 text-sm">
            <button class="w-full bg-purple-600 hover:bg-purple-500 p-3 rounded-lg transition font-medium">
                Guardar
            </button>
        </form>
        <button onclick="closePlaylistModal()"
                class="mt-3 w-full text-center text-gray-400 hover:text-white text-sm transition">
            Cancelar
        </button>
    </div>
</div>

<script>
function openSidebar() {
    document.getElementById('sidebar').classList.remove('-translate-x-full');
    document.getElementById('overlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('sidebar').classList.add('-translate-x-full');
    document.getElementById('overlay').classList.add('hidden');
    document.body.style.overflow = '';
}
function openPlaylistModal() {
    const m = document.getElementById('playlistModal');
    m.classList.remove('hidden');
    m.classList.add('flex');
}
function closePlaylistModal() {
    const m = document.getElementById('playlistModal');
    m.classList.add('hidden');
    m.classList.remove('flex');
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeSidebar(); closePlaylistModal(); }
});
</script>

</body>
</html>