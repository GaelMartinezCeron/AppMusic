<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label>Nombre</label>
            <input type="text" name="name" required class="block w-full">
        </div>

        <div class="mt-4">
            <label>Email</label>
            <input type="email" name="email" required class="block w-full">
        </div>

        <div class="mt-4">
            <label>Contraseña</label>
            <input type="password" name="password" required class="block w-full">
        </div>

        <div class="mt-4">
            <label>Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" required class="block w-full">
        </div>

        <div class="mt-4">
            <button class="bg-blue-500 text-black px-4 py-2">
                Registrarse
            </button>
        </div>
    </form>
</x-guest-layout>