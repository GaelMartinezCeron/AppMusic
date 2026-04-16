<x-app-layout>
    <div class="p-6">

        <h1 class="text-2xl font-bold mb-4">
            Bienvenido, {{ auth()->user()->name }}
        </h1>

        <p class="mb-6 text-gray-600">
            Tu música recomendada 🎧
        </p>

        <div class="grid grid-cols-3 gap-4">
            @foreach($songs as $song)
                <div class="bg-white shadow rounded p-4">

                    <h2 class="font-bold">{{ $song->title }}</h2>
                    <p class="text-sm text-gray-500">{{ $song->artist }}</p>

                    <audio controls class="mt-2 w-full">
                        <source src="{{ asset('storage/' . $song->file_path) }}" type="audio/mpeg">
                    </audio>

                </div>
            @endforeach
        </div>

    </div>
</x-app-layout>