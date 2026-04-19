<x-app-layout>

<h1 class="text-2xl font-bold mb-6">🎧 DJ LIVE</h1>

<div class="text-gray-400 mb-4">
    Reproducción automática basada en tus gustos
</div>

<div class="bg-gray-900 p-6 rounded-lg text-center">

    <img id="cover" src="" class="w-48 h-48 mx-auto rounded shadow mb-4 object-cover">

    <h2 id="title" class="text-xl font-bold"></h2>
    <p id="artist" class="text-gray-400 mb-4"></p>

    <button onclick="togglePlay()" class="bg-purple-600 px-6 py-2 rounded">
        ▶ Play / Pause
    </button>

</div>

<audio id="player" class="hidden"></audio>

<script>
const songs = @json($songsData);
let currentIndex = 0;
let player = document.getElementById('player');

function loadSong(index){
    let song = songs[index];

    fetch('/play/' + song.id)
        .then(res => res.json())
        .then(data => {

            if(data.error){
                console.log("Error");
                return;
            }

            player.src = '/storage/' + data.file_path;

            document.getElementById('title').innerText = data.title;
            document.getElementById('artist').innerText = data.artist;
            document.getElementById('cover').src = '/storage/' + data.image;

            player.play();
        });
}

function nextSong(){
    currentIndex++;

    if(currentIndex >= songs.length){
        currentIndex = 0; // loop infinito
    }

    loadSong(currentIndex);
}

function togglePlay(){
    if(player.paused){
        player.play();
    } else {
        player.pause();
    }
}

player.addEventListener('ended', nextSong);

// AUTO START
if(songs.length > 0){
    loadSong(0);
}
</script>

</x-app-layout>