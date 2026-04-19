<x-guest-layout>
    <style>
        /* Mantenemos la consistencia del fondo */
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
            transition: all 0.3s ease;
        }

        .apple-input:focus {
            outline: none !important;
            border-color: #a855f7 !important;
            background: rgba(255, 255, 255, 0.12) !important;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.2);
        }

        .apple-button {
            background: linear-gradient(90deg, #a855f7, #ec4899) !important;
            border-radius: 2rem !important;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .apple-button:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(168, 85, 247, 0.3);
        }
    </style>

    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full apple-card p-8 md:p-10">
            
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white tracking-tight">Crea tu cuenta</h2>
                <p class="mt-2 text-gray-400 text-sm">Únete para empezar a escuchar</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-gray-400 text-xs mb-1 ml-1">Nombre completo</label>
                    <input type="text" name="name" :value="old('name')" required autofocus 
                           class="apple-input w-full" placeholder="Tu nombre">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-gray-400 text-xs mb-1 ml-1">Correo electrónico</label>
                    <input type="email" name="email" :value="old('email')" required 
                           class="apple-input w-full" placeholder="correo@ejemplo.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-gray-400 text-xs mb-1 ml-1">Contraseña</label>
                    <input type="password" name="password" required 
                           class="apple-input w-full" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-gray-400 text-xs mb-1 ml-1">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" required 
                           class="apple-input w-full" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="pt-2">
                    <button type="submit" class="apple-button w-full py-3 text-white font-bold uppercase tracking-widest text-sm">
                        REGISTRARSE
                    </button>
                </div>

                <div class="text-center mt-4">
                    <p class="text-gray-400 text-sm">
                        ¿Ya tienes cuenta? 
                        <a href="{{ route('login') }}" class="text-purple-400 font-bold hover:underline">Inicia sesión</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>