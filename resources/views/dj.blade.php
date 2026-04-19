<x-app-layout>

<style>
    .progress-bar {
        -webkit-appearance: none;
        background: #3e3e3e;
        height: 4px;
        border-radius: 4px;
        width: 100%;
    }
    .progress-bar::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #fff;
        cursor: pointer;
        box-shadow: 0 0 4px rgba(0,0,0,0.5);
    }
    .volume-slider {
        -webkit-appearance: none;
        background: #3e3e3e;
        height: 3px;
        border-radius: 3px;
    }
    .volume-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #fff;
        cursor: pointer;
    }
    #cover {
        transition: transform 0.5s ease, box-shadow 0.5s ease;
    }
    #cover.playing {
        transform: scale(1.03);
        box-shadow: 0 30px 60px rgba(139, 92, 246, 0.4);
    }
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }
    #cover.spinning {
        animation: spin-slow 12s linear infinite;
        border-radius: 50%;
    }
</style>

<div class="flex flex-col w-full flex-1 bg-gradient-to-b from-gray-900 via-purple-900/20 to-black text-white overflow-x-hidden">

    {{-- HEADER --}}
    <div class="px-4 sm:px-8 pt-6 pb-2">
        <h1 class="text-xl sm:text-2xl font-bold">🎧 DJ LIVE</h1>
        <p class="text-gray-400 text-sm mt-1">Reproducción automática basada en tus gustos</p>
    </div>

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="flex-1 flex flex-col lg:flex-row items-center justify-center gap-6 md:gap-8 px-4 sm:px-8 py-6">

        {{-- PORTADA --}}
        <div class="w-full max-w-xs sm:max-w-sm lg:max-w-md flex-shrink-0">
            <div class="relative">
                <img id="cover"
                     src="https://via.placeholder.com/400x400/1f1f2e/8b5cf6?text=DJ+LIVE"
                     class="w-full aspect-square object-cover rounded-2xl shadow-2xl">
                {{-- Glow de fondo --}}
                <div id="coverGlow" class="absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-1000"
                     style="background: radial-gradient(ellipse at center, rgba(139,92,246,0.3) 0%, transparent 70%); filter: blur(20px); transform: scale(1.1); z-index: -1;"></div>
            </div>
        </div>

        {{-- PANEL DE CONTROLES --}}
        <div class="w-full max-w-md flex flex-col gap-5 min-w-0">

            {{-- Info canción --}}
            <div class="text-center lg:text-left">
                <h2 id="title" class="text-2xl sm:text-3xl font-bold truncate">Cargando...</h2>
                <p id="artist" class="text-gray-400 text-base sm:text-lg mt-1 truncate"></p>
                <div id="crossfadeIndicator" class="hidden mt-2 inline-flex items-center gap-1 text-xs text-purple-400 bg-purple-400/10 px-3 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 bg-purple-400 rounded-full animate-pulse"></span>
                    Mezclando siguiente canción...
                </div>
            </div>

            {{-- Barra de progreso --}}
            <div class="flex items-center gap-3">
                <span id="currentTime" class="text-xs text-gray-400 tabular-nums w-10 text-right shrink-0">0:00</span>
                <input type="range" id="progressBar" class="progress-bar flex-1" value="0" step="0.01" min="0" max="100">
                <span id="durationTime" class="text-xs text-gray-400 tabular-nums w-10 shrink-0">0:00</span>
            </div>

            {{-- Controles principales --}}
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 sm:gap-6">
                {{-- Shuffle --}}
                <button id="shuffleBtn" class="text-gray-500 hover:text-white transition p-1" title="Aleatorio">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10.59 9.17L5.41 4 4 5.41l5.17 5.17 1.42-1.41zM14.5 4l2.04 2.04L4 18.59 5.41 20 17.96 7.46 20 9.5V4h-5.5zm.33 9.41l-1.41 1.41 3.13 3.13L14.5 20H20v-5.5l-2.04 2.04-3.13-3.13z"/>
                    </svg>
                </button>

                {{-- Prev --}}
                <button id="prevBtn" class="text-gray-300 hover:text-white transition p-1">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/>
                    </svg>
                </button>

                {{-- Play/Pause --}}
                <button id="playPauseBtn"
                    class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-white text-black flex items-center justify-center shadow-xl hover:scale-105 active:scale-95 transition">
                    <svg id="playIcon" class="w-7 h-7 ml-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                    <svg id="pauseIcon" class="w-7 h-7 hidden" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                    </svg>
                </button>

                {{-- Next --}}
                <button id="nextBtn" class="text-gray-300 hover:text-white transition p-1">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z"/>
                    </svg>
                </button>

                {{-- Repeat --}}
                <button id="repeatBtn" class="text-gray-500 hover:text-white transition p-1" title="Repetir">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 7h10v3l4-4-4-4v3H5v6h2V7zm10 10H7v-3l-4 4 4 4v-3h12v-6h-2v4z"/>
                    </svg>
                </button>
            </div>

            {{-- Volumen --}}
            <div class="flex items-center gap-3">
                <button id="muteBtn" class="text-gray-400 hover:text-white transition shrink-0 p-1">
                    <svg id="volIcon" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                    </svg>
                    <svg id="muteIcon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
                    </svg>
                </button>
                <input type="range" id="volumeControl" class="volume-slider flex-1" min="0" max="1" step="0.01" value="0.7">
            </div>

            {{-- Crossfade slider --}}
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500 shrink-0">Crossfade</span>
                <input type="range" id="crossfadeControl" class="volume-slider flex-1" min="1" max="12" step="1" value="5">
                <span id="crossfadeValue" class="text-xs text-gray-400 w-8 shrink-0 text-right">5s</span>
            </div>

        </div>
    </div>
</div>

<script>
const songs = @json($songsData);
let currentIndex = 0;
let isSeeking = false;
let activePlayer = 'A';
let crossfadeStarted = false;
let animFrameId = null;
let MASTER_VOLUME = 0.7;
let CROSSFADE_DURATION = 5;
let isMuted = false;
let prevVolume = 0.7;

const playerA = new Audio();
const playerB = new Audio();
playerA.volume = MASTER_VOLUME;
playerB.volume = 0;

const progressBar       = document.getElementById('progressBar');
const currentTimeSpan   = document.getElementById('currentTime');
const durationSpan      = document.getElementById('durationTime');
const playIcon          = document.getElementById('playIcon');
const pauseIcon         = document.getElementById('pauseIcon');
const volumeControl     = document.getElementById('volumeControl');
const crossfadeControl  = document.getElementById('crossfadeControl');
const crossfadeValue    = document.getElementById('crossfadeValue');
const crossfadeIndicator = document.getElementById('crossfadeIndicator');
const cover             = document.getElementById('cover');
const volIcon           = document.getElementById('volIcon');
const muteIcon          = document.getElementById('muteIcon');

function getActive()   { return activePlayer === 'A' ? playerA : playerB; }
function getInactive() { return activePlayer === 'A' ? playerB : playerA; }

function formatTime(s) {
    if (isNaN(s) || !isFinite(s)) return '0:00';
    return `${Math.floor(s / 60)}:${String(Math.floor(s % 60)).padStart(2, '0')}`;
}

function setPlayingState(playing) {
    playIcon.classList.toggle('hidden', playing);
    pauseIcon.classList.toggle('hidden', !playing);
    cover.classList.toggle('playing', playing);
}

function updateUI(data) {
    document.getElementById('title').innerText = data.title;
    document.getElementById('artist').innerText = data.artist;
    cover.src = data.image.startsWith('http') ? data.image : '/storage/' + data.image;
    progressBar.value = 0;
    currentTimeSpan.innerText = '0:00';
    durationSpan.innerText = '0:00';
}

function loadSong(index, autoPlay = true) {
    if (!songs[index]) return;
    crossfadeStarted = false;
    crossfadeIndicator.classList.add('hidden');

    if (animFrameId) cancelAnimationFrame(animFrameId);
    playerA.pause(); playerA.src = '';
    playerB.pause(); playerB.src = '';
    activePlayer = 'A';
    playerA.volume = MASTER_VOLUME;
    playerB.volume = 0;

    fetch('/play/' + songs[index].id)
        .then(r => r.json())
        .then(data => {
            if (data.error) return;
            updateUI(data);
            playerA.src = '/audio/' + data.file_path;
            playerA.load();
            if (autoPlay) {
                playerA.play()
                    .then(() => setPlayingState(true))
                    .catch(console.error);
            }
        })
        .catch(console.error);
}

function crossfadeTo(nextIndex) {
    if (!songs[nextIndex] || crossfadeStarted) return;
    crossfadeStarted = true;
    crossfadeIndicator.classList.remove('hidden');

    if (animFrameId) cancelAnimationFrame(animFrameId);

    fetch('/play/' + songs[nextIndex].id)
        .then(r => r.json())
        .then(data => {
            if (data.error) return;

            const outgoing = getActive();
            activePlayer = activePlayer === 'A' ? 'B' : 'A';
            const incoming = getActive();

            incoming.src = '/audio/' + data.file_path;
            incoming.volume = 0;
            incoming.load();
            incoming.play().catch(console.error);

            updateUI(data);
            currentIndex = nextIndex;

            const startTime = performance.now();
            function animateFade(now) {
                const progress = Math.min((now - startTime) / 1000 / CROSSFADE_DURATION, 1);
                outgoing.volume = Math.max(0, MASTER_VOLUME * (1 - progress));
                incoming.volume = Math.min(MASTER_VOLUME, MASTER_VOLUME * progress);

                if (progress < 1) {
                    animFrameId = requestAnimationFrame(animateFade);
                } else {
                    outgoing.pause();
                    outgoing.src = '';
                    outgoing.volume = 0;
                    crossfadeIndicator.classList.add('hidden');
                }
            }
            animFrameId = requestAnimationFrame(animateFade);
        })
        .catch(console.error);
}

function nextSong() {
    crossfadeStarted = false;
    crossfadeTo((currentIndex + 1) % songs.length);
}

function prevSong() {
    currentIndex = (currentIndex - 1 + songs.length) % songs.length;
    loadSong(currentIndex);
}

function togglePlay() {
    const player = getActive();
    if (player.paused) {
        player.play().then(() => setPlayingState(true));
    } else {
        player.pause();
        setPlayingState(false);
    }
}

function setupPlayer(player) {
    player.addEventListener('timeupdate', () => {
        if (player !== getActive() || isSeeking || !player.duration) return;
        progressBar.value = player.currentTime;
        currentTimeSpan.innerText = formatTime(player.currentTime);
        const remaining = player.duration - player.currentTime;
        if (remaining <= CROSSFADE_DURATION && remaining > 0 && !crossfadeStarted) {
            nextSong();
        }
    });

    player.addEventListener('loadedmetadata', () => {
        if (player !== getActive()) return;
        progressBar.max = player.duration;
        durationSpan.innerText = formatTime(player.duration);
    });

    player.addEventListener('durationchange', () => {
        if (player !== getActive() || !isFinite(player.duration)) return;
        progressBar.max = player.duration;
        durationSpan.innerText = formatTime(player.duration);
    });

    player.addEventListener('play',  () => { if (player === getActive()) setPlayingState(true); });
    player.addEventListener('pause', () => { if (player === getActive()) setPlayingState(false); });
}

setupPlayer(playerA);
setupPlayer(playerB);

// Barra de progreso
progressBar.addEventListener('mousedown', () => { isSeeking = true; });
progressBar.addEventListener('touchstart', () => { isSeeking = true; }, { passive: true });
progressBar.addEventListener('input', function() {
    if (isSeeking) currentTimeSpan.innerText = formatTime(parseFloat(this.value));
});
progressBar.addEventListener('change', function() {
    getActive().currentTime = parseFloat(this.value);
    isSeeking = false;
});
progressBar.addEventListener('touchend', function() {
    getActive().currentTime = parseFloat(this.value);
    isSeeking = false;
});

// Botones
document.getElementById('playPauseBtn').addEventListener('click', togglePlay);
document.getElementById('nextBtn').addEventListener('click', nextSong);
document.getElementById('prevBtn').addEventListener('click', prevSong);

// Mute
document.getElementById('muteBtn').addEventListener('click', () => {
    isMuted = !isMuted;
    getActive().muted = isMuted;
    volIcon.classList.toggle('hidden', isMuted);
    muteIcon.classList.toggle('hidden', !isMuted);
});

// Volumen
volumeControl.addEventListener('input', function() {
    MASTER_VOLUME = parseFloat(this.value);
    if (!isMuted) getActive().volume = MASTER_VOLUME;
    volIcon.classList.toggle('hidden', MASTER_VOLUME === 0);
    muteIcon.classList.toggle('hidden', MASTER_VOLUME > 0);
});

// Crossfade duration
crossfadeControl.addEventListener('input', function() {
    CROSSFADE_DURATION = parseInt(this.value);
    crossfadeValue.innerText = CROSSFADE_DURATION + 's';
});

// Autostart
if (songs.length > 0) loadSong(0);
</script>

</x-app-layout>