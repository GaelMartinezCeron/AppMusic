<x-guest-layout>
    <style>
        /* Fondo premium forzado */
        body, .min-h-screen {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%) !important;
        }

        .apple-card {
            background: rgba(20, 20, 30, 0.8) !important;
            backdrop-filter: blur(20px);
            border-radius: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.4);
        }

        .apple-input {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 0.75rem !important;
            color: white !important;
            padding: 0.85rem 1rem !important;
        }

        .apple-button {
            background: linear-gradient(90deg, #a855f7, #ec4899) !important;
            border-radius: 2rem !important;
            transition: all 0.3s ease;
        }
    </style>

    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full space-y-8 apple-card p-8 md:p-10">
            
            <div class="text-center">
                <div class="mx-auto w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg mb-5">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white tracking-tight">Bienvenido de nuevo</h2>
                <p class="mt-2 text-gray-400 text-sm">Inicia sesión para disfrutar de la mejor música</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label class="block text-gray-400 text-xs mb-1 ml-1">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="apple-input w-full" placeholder="gaelceron45@gmail.com">
                </div>

                <div>
                    <label class="block text-gray-400 text-xs mb-1 ml-1">Contraseña</label>
                    <input type="password" name="password" required
                           class="apple-input w-full" placeholder="••••••••••••">
                </div>

                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center text-gray-400 text-sm">
                        <input type="checkbox" name="remember" class="rounded bg-gray-800 border-gray-700 text-purple-500 focus:ring-purple-500">
                        <span class="ml-2">Recordarme</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-purple-400 text-sm hover:underline">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <button type="submit" class="apple-button w-full py-3 text-white font-bold uppercase tracking-widest text-sm">
                    INICIAR SESIÓN
                </button>

                <div class="text-center pt-2">
                    <p class="text-gray-400 text-sm">
                        ¿No tienes cuenta? 
                        <a href="{{ route('register') }}" class="text-purple-400 font-bold hover:underline">Regístrate</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>