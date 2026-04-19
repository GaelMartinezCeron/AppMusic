@php use Illuminate\Support\Str; @endphp
<x-app-layout>
    
    {{-- Estilos adicionales para animaciones y personalización --}}
    <style>
        /* Transición suave para las tarjetas */
        .song-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .song-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.5);
        }
        /* Barra de progreso personalizada estilo Apple */
        .progress-bar {
            -webkit-appearance: none;
            background: #3e3e3e;
            height: 4px;
            border-radius: 4px;
        }
        .progress-bar::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #fff;
            cursor: pointer;
            box-shadow: 0 0 2px rgba(0,0,0,0.5);
        }
        .progress-bar::-webkit-slider-thumb:hover {
            transform: scale(1.2);
        }
        /* Volumen */
        .volume-slider {
            -webkit-appearance: none;
            background: #3e3e3e;
            height: 3px;
            border-radius: 3px;
        }
        .volume-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #fff;
            cursor: pointer;
        }
        /* Animación de pulso para el botón de reproducción */
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.6; }
            100% { transform: scale(1.2); opacity: 0; }
        }
        .playing-indicator {
            animation: pulse-ring 1.5s infinite;
        }
    </style>

    <div class="min-h-screen bg-gradient-to-b from-gray-900 via-purple-900/20 to-black text-white pb-32">
        {{-- Encabezado con gradiente y blur --}}
        <div class="sticky top-0 z-10 bg-black/50 backdrop-blur-md border-b border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight bg-gradient-to-r from-white to-purple-300 bg-clip-text text-transparent">
                        Buenas tardes, {{ auth()->user()->name }}
                    </h1>
                    <p class="text-sm text-gray-300 mt-1">Descubre lo mejor de la música</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="p-2 rounded-full bg-white/10 hover:bg-white/20 transition">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    <img src="https://ui-avatars.com/api/?background=8b5cf6&color=fff&name={{ auth()->user()->name }}" 
                         class="w-9 h-9 rounded-full ring-2 ring-purple-500" alt="Avatar">
                </div>
            </div>
        </div>

        {{-- Grid de canciones --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold tracking-tight">Tus canciones</h2>
                <a href="#" class="text-sm text-purple-400 hover:text-purple-300 transition">Ver todo →</a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                @foreach($songs as $index => $song)
                <div class="song-card bg-white/5 backdrop-blur-sm rounded-xl overflow-hidden cursor-pointer group border border-white/10"
                     data-id="{{ $song->id }}" data-index="{{ $index }}">
                    <div class="relative">
                        <img src="{{ asset('storage/' . $song->image) }}" 
                             class="w-full aspect-square object-cover transition duration-300 group-hover:scale-105"
                             alt="{{ $song->title }}">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <button class="play-btn w-12 h-12 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white flex items-center justify-center shadow-lg transform scale-90 group-hover:scale-100 transition">
                                <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-3">
                        <h3 class="font-semibold text-sm truncate">{{ $song->title }}</h3>
                        <p class="text-xs text-gray-300 truncate">{{ $song->artist }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Reproductor global fijo estilo Apple Music --}}
    <div id="playerBar" class="fixed bottom-0 left-0 right-0 bg-black/80 backdrop-blur-2xl border-t border-white/20 shadow-2xl z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex flex-col sm:flex-row items-center gap-3">
                {{-- Info de la canción actual --}}
                <div class="flex items-center gap-3 w-full sm:w-1/3">
                    <img id="nowPlayingImage" src="" class="w-12 h-12 rounded-md object-cover shadow-md">
                    <div class="flex-1 min-w-0">
                        <h4 id="nowPlayingTitle" class="text-sm font-semibold truncate">Ninguna canción</h4>
                        <p id="nowPlayingArtist" class="text-xs text-gray-300 truncate">Selecciona una canción</p>
                    </div>
                    <button id="likeBtn" class="text-gray-400 hover:text-purple-400 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </div>

                {{-- Controles de reproducción --}}
                <div class="flex flex-col items-center gap-2 w-full sm:w-1/3">
                    <div class="flex items-center gap-5">
                        <button id="prevBtn" class="text-gray-300 hover:text-white transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/>
                            </svg>
                        </button>
                        <button id="playPauseBtn" class="w-10 h-10 rounded-full bg-white text-black flex items-center justify-center shadow-lg hover:scale-105 transition">
                            <svg id="playIcon" class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            <svg id="pauseIcon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                            </svg>
                        </button>
                        <button id="nextBtn" class="text-gray-300 hover:text-white transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center gap-2 w-full">
                        <span id="currentTime" class="text-xs text-gray-300">0:00</span>
                        <input type="range" id="progressBar" class="progress-bar flex-1 h-1 rounded-full bg-gray-600" value="0" step="0.01">
                        <span id="durationTime" class="text-xs text-gray-300">0:00</span>
                    </div>
                </div>

                {{-- Control de volumen --}}
                <div class="hidden sm:flex items-center justify-end gap-4 w-1/3">
                    <button id="muteBtn" class="text-gray-300 hover:text-white">
                        <svg id="volumeHighIcon" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 9v6h4l5 5V4L7 9H3z"/>
                        </svg>
                        <svg id="volumeMuteIcon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
                        </svg>
                    </button>
                    <input type="range" id="volumeControl" class="volume-slider w-24 h-1 rounded-full bg-gray-600" min="0" max="1" step="0.01" value="0.7">
                </div>
            </div>
        </div>
    </div>

    <audio id="audioPlayer" class="hidden"></audio>

    <script>
document.addEventListener('DOMContentLoaded', function() {

    const songsData = @json($songsData);
    let currentIndex = 0;
    let isSeeking = false;

    const audio = document.getElementById('audioPlayer');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const playIcon = document.getElementById('playIcon');
    const pauseIcon = document.getElementById('pauseIcon');
    const progressBar = document.getElementById('progressBar');
    const currentTimeSpan = document.getElementById('currentTime');
    const durationSpan = document.getElementById('durationTime');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const nowPlayingTitle = document.getElementById('nowPlayingTitle');
    const nowPlayingArtist = document.getElementById('nowPlayingArtist');
    const nowPlayingImage = document.getElementById('nowPlayingImage');
    const volumeControl = document.getElementById('volumeControl');
    const muteBtn = document.getElementById('muteBtn');
    const volumeHighIcon = document.getElementById('volumeHighIcon');
    const volumeMuteIcon = document.getElementById('volumeMuteIcon');

    audio.volume = 0.7;

    function formatTime(seconds) {
        if (isNaN(seconds) || !isFinite(seconds)) return '0:00';
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }

    function setPlayingState(playing) {
        playIcon.classList.toggle('hidden', playing);
        pauseIcon.classList.toggle('hidden', !playing);
    }

    function loadSong(index, autoPlay = true) {
        const song = songsData[index];
        if (!song) return;

        fetch(`/play/${song.id}`)
            .then(res => res.json())
            .then(data => {
                const fileUrl = `${window.location.origin}/storage/${data.file_path}`;
                
                audio.src = fileUrl;
                nowPlayingTitle.innerText = data.title;
                nowPlayingArtist.innerText = data.artist;
                nowPlayingImage.src = data.image.startsWith('http')
                    ? data.image
                    : `${window.location.origin}/storage/${data.image}`;

                progressBar.value = 0;
                progressBar.max = 0;
                currentTimeSpan.innerText = '0:00';
                durationSpan.innerText = '0:00';

                audio.load();

                if (autoPlay) {
                    audio.play().then(() => setPlayingState(true)).catch(console.error);
                }
            })
            .catch(console.error);
    }

    function nextSong() {
        currentIndex = (currentIndex + 1) % songsData.length;
        loadSong(currentIndex, true);
    }

    function prevSong() {
        currentIndex = (currentIndex - 1 + songsData.length) % songsData.length;
        loadSong(currentIndex, true);
    }

    // ✅ Solución robusta: usar 'change' en lugar de mouseup/touchend
    progressBar.addEventListener('mousedown', () => {
        isSeeking = true;
    });

    progressBar.addEventListener('change', function() {
        // 'change' se dispara al SOLTAR el mouse/dedo
        const seekTo = parseFloat(this.value);
        console.log('Seeking to:', seekTo, '| Duration:', audio.duration);
        audio.currentTime = seekTo;
        isSeeking = false;
    });

    progressBar.addEventListener('input', function() {
        // Solo actualiza el texto mientras arrastra
        if (isSeeking) {
            currentTimeSpan.innerText = formatTime(parseFloat(this.value));
        }
    });

    audio.addEventListener('timeupdate', () => {
        if (!isSeeking && audio.duration) {
            progressBar.value = audio.currentTime;
            currentTimeSpan.innerText = formatTime(audio.currentTime);
        }
    });

    audio.addEventListener('loadedmetadata', () => {
        console.log('Metadata loaded. Duration:', audio.duration);
        progressBar.max = audio.duration;
        durationSpan.innerText = formatTime(audio.duration);
    });

    // Por si loadedmetadata ya pasó antes de asignar el evento
    audio.addEventListener('durationchange', () => {
        if (audio.duration && isFinite(audio.duration)) {
            progressBar.max = audio.duration;
            durationSpan.innerText = formatTime(audio.duration);
        }
    });

    playPauseBtn.addEventListener('click', () => {
        if (audio.paused) {
            audio.play().then(() => setPlayingState(true));
        } else {
            audio.pause();
            setPlayingState(false);
        }
    });

    audio.addEventListener('play', () => setPlayingState(true));
    audio.addEventListener('pause', () => setPlayingState(false));
    audio.addEventListener('ended', nextSong);

    nextBtn.addEventListener('click', nextSong);
    prevBtn.addEventListener('click', prevSong);

    volumeControl.addEventListener('input', function() {
        audio.volume = this.value;
        volumeHighIcon.classList.toggle('hidden', this.value == 0);
        volumeMuteIcon.classList.toggle('hidden', this.value > 0);
    });

    muteBtn.addEventListener('click', () => {
        audio.muted = !audio.muted;
        volumeHighIcon.classList.toggle('hidden', audio.muted);
        volumeMuteIcon.classList.toggle('hidden', !audio.muted);
    });

    document.querySelectorAll('.song-card').forEach((card, idx) => {
        card.addEventListener('click', () => {
            currentIndex = idx;
            loadSong(currentIndex, true);
        });
    });
});
</script>
</x-app-layout>