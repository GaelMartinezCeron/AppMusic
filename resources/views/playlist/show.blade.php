<x-app-layout>

<style>
    .song-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .song-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.5);
    }
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
</style>

<div class="min-h-screen bg-gradient-to-b from-gray-900 via-purple-900/20 to-black text-white pb-32 px-4">
    <div class="max-w-7xl mx-auto pt-8">

        <h1 class="text-2xl font-bold mb-2">{{ $playlist->name }}</h1>
        <p class="text-gray-400 mb-6">{{ $playlist->songs->count() }} canciones</p>

        {{-- AGREGAR CANCIONES --}}
        <form method="POST" action="/playlist/add-song" class="mb-8">
            @csrf
            <input type="hidden" name="playlist_id" value="{{ $playlist->id }}">
            <div class="flex gap-2">
                <select name="song_id" class="bg-gray-800 text-white p-2 rounded-lg w-full border border-white/10">
                    @foreach($songs as $song)
                        <option value="{{ $song->id }}">{{ $song->title }} - {{ $song->artist }}</option>
                    @endforeach
                </select>
                <button class="bg-purple-600 px-5 py-2 rounded-lg hover:bg-purple-500 transition whitespace-nowrap">
                    + Agregar
                </button>
            </div>
        </form>

        {{-- GRID DE CANCIONES --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            @foreach($playlist->songs as $index => $song)
            <div class="song-card bg-white/5 backdrop-blur-sm rounded-xl overflow-hidden cursor-pointer group border border-white/10"
                 data-index="{{ $index }}" data-id="{{ $song->id }}">
                <div class="relative">
                    <img src="{{ asset('storage/' . $song->image) }}"
                         class="w-full aspect-square object-cover transition duration-300 group-hover:scale-105"
                         alt="{{ $song->title }}">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 ml-0.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
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

{{-- REPRODUCTOR FIJO --}}
<div class="fixed bottom-0 left-0 right-0 bg-black/80 backdrop-blur-2xl border-t border-white/20 shadow-2xl z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex flex-col sm:flex-row items-center gap-3">

            {{-- Info canción --}}
            <div class="flex items-center gap-3 w-full sm:w-1/3">
                <img id="nowPlayingImage" src="" class="w-12 h-12 rounded-md object-cover shadow-md bg-gray-800">
                <div class="flex-1 min-w-0">
                    <h4 id="nowPlayingTitle" class="text-sm font-semibold truncate">Ninguna canción</h4>
                    <p id="nowPlayingArtist" class="text-xs text-gray-300 truncate">Selecciona una canción</p>
                </div>
            </div>

            {{-- Controles --}}
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
                    <input type="range" id="progressBar" class="progress-bar flex-1 h-1 rounded-full bg-gray-600" value="0" step="0.01" min="0">
                    <span id="durationTime" class="text-xs text-gray-300">0:00</span>
                </div>
            </div>

            {{-- Volumen --}}
            <div class="hidden sm:flex items-center justify-end gap-3 w-1/3">
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 9v6h4l5 5V4L7 9H3z"/>
                </svg>
                <input type="range" id="volumeControl" class="volume-slider w-24" min="0" max="1" step="0.01" value="0.7">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                </svg>
            </div>

        </div>
    </div>
</div>

<audio id="audioPlayer" class="hidden"></audio>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Construir lista de canciones desde las tarjetas
    const songCards = document.querySelectorAll('.song-card');
    const songsData = Array.from(songCards).map(card => ({
        id: card.dataset.id,
        index: parseInt(card.dataset.index)
    }));

    let currentIndex = 0;
    let isSeeking = false;

    const audio = document.getElementById('audioPlayer');
    const progressBar = document.getElementById('progressBar');
    const currentTimeSpan = document.getElementById('currentTime');
    const durationSpan = document.getElementById('durationTime');
    const playIcon = document.getElementById('playIcon');
    const pauseIcon = document.getElementById('pauseIcon');
    const nowPlayingTitle = document.getElementById('nowPlayingTitle');
    const nowPlayingArtist = document.getElementById('nowPlayingArtist');
    const nowPlayingImage = document.getElementById('nowPlayingImage');
    const volumeControl = document.getElementById('volumeControl');

    audio.volume = 0.7;

    function formatTime(s) {
        if (isNaN(s) || !isFinite(s)) return '0:00';
        return `${Math.floor(s / 60)}:${String(Math.floor(s % 60)).padStart(2, '0')}`;
    }

    function setPlayingState(playing) {
        playIcon.classList.toggle('hidden', playing);
        pauseIcon.classList.toggle('hidden', !playing);
    }

    function loadSong(index, autoPlay = true) {
        if (!songsData[index]) return;
        const song = songsData[index];

        fetch('/play/' + song.id)
            .then(res => res.json())
            .then(data => {
                if (data.error) return;

                // ✅ Usa /audio/ para soporte de Range requests (seek)
                audio.src = '/audio/' + data.file_path;
                nowPlayingTitle.innerText = data.title;
                nowPlayingArtist.innerText = data.artist;
                nowPlayingImage.src = data.image.startsWith('http')
                    ? data.image
                    : '/storage/' + data.image;

                progressBar.value = 0;
                progressBar.max = 0;
                currentTimeSpan.innerText = '0:00';
                durationSpan.innerText = '0:00';

                audio.load();
                if (autoPlay) {
                    audio.play().then(() => setPlayingState(true)).catch(console.error);
                }
            });
    }

    function nextSong() {
        currentIndex = (currentIndex + 1) % songsData.length;
        loadSong(currentIndex);
    }

    function prevSong() {
        currentIndex = (currentIndex - 1 + songsData.length) % songsData.length;
        loadSong(currentIndex);
    }

    // Clicks en tarjetas
    songCards.forEach((card, idx) => {
        card.addEventListener('click', () => {
            currentIndex = idx;
            loadSong(currentIndex);
        });
    });

    // Play/Pause
    document.getElementById('playPauseBtn').addEventListener('click', () => {
        if (audio.paused) {
            audio.play().then(() => setPlayingState(true));
        } else {
            audio.pause();
            setPlayingState(false);
        }
    });

    document.getElementById('nextBtn').addEventListener('click', nextSong);
    document.getElementById('prevBtn').addEventListener('click', prevSong);

    // Barra de progreso con seek
    progressBar.addEventListener('mousedown', () => { isSeeking = true; });
    progressBar.addEventListener('touchstart', () => { isSeeking = true; });
    progressBar.addEventListener('input', function () {
        if (isSeeking) currentTimeSpan.innerText = formatTime(parseFloat(this.value));
    });
    progressBar.addEventListener('change', function () {
        audio.currentTime = parseFloat(this.value);
        isSeeking = false;
    });
    progressBar.addEventListener('touchend', function () {
        audio.currentTime = parseFloat(this.value);
        isSeeking = false;
    });

    audio.addEventListener('timeupdate', () => {
        if (!isSeeking && audio.duration) {
            progressBar.value = audio.currentTime;
            currentTimeSpan.innerText = formatTime(audio.currentTime);
        }
    });
    audio.addEventListener('loadedmetadata', () => {
        progressBar.max = audio.duration;
        durationSpan.innerText = formatTime(audio.duration);
    });
    audio.addEventListener('durationchange', () => {
        if (audio.duration && isFinite(audio.duration)) {
            progressBar.max = audio.duration;
            durationSpan.innerText = formatTime(audio.duration);
        }
    });
    audio.addEventListener('play', () => setPlayingState(true));
    audio.addEventListener('pause', () => setPlayingState(false));
    audio.addEventListener('ended', nextSong);

    // Volumen
    volumeControl.addEventListener('input', function () {
        audio.volume = this.value;
    });

});
</script>

</x-app-layout>