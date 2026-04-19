<x-app-layout>

<div class="max-w-7xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-6">🎧 DJ LIVE</h1>

    <div class="bg-white/5 p-6 rounded-xl shadow-lg">

        <audio id="djPlayer" controls class="w-full"></audio>

    </div>

</div>

<script>
let playlist = @json($songsData ?? []);
let index = 0;

let player = document.getElementById('djPlayer');

function playNext(){
    if(playlist.length === 0) return;

    let song = playlist[index];
    player.src = "/storage/" + song.file_path;
    player.play();

    index = (index + 1) % playlist.length;
}

player.addEventListener('ended', playNext);
playNext();
</script>

</x-app-layout>