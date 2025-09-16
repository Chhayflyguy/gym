<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gym Bro Tracker</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="bg-gradient-to-br from-indigo-950 via-gray-950 to-purple-950 text-gray-200 flex items-center justify-center min-h-screen relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/black-linen.png')] opacity-10"></div>
            <div class="relative z-10 w-full">
                <header class="absolute top-0 left-0 right-0 p-6 lg:p-8">
                    @if (Route::has('login'))
                        <nav class="flex items-center justify-end gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-blue-400 focus:outline-none">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-blue-400 focus:outline-none">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-blue-400 focus:outline-none">Register</a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </header>
                <main class="flex items-center justify-center min-h-screen px-6">
                    <div x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 200)" class="text-center max-w-2xl mx-auto">
                        <div x-show="shown" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="flex justify-center mb-8">
                            <img src="{{ asset('images/wel.jpg') }}" alt="Gym Bro Tracker Logo" class="w-40 h-auto filter drop-shadow-lg">
                        </div>
                        <h1 x-show="shown" x-transition:enter="transition ease-out duration-500 delay-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="text-5xl md:text-8xl font-extrabold text-white uppercase tracking-tighter leading-none">
                            gym <br/><span class="text-blue-400 drop-shadow-md">bro(s)</span>
                        </h1>
                        <p x-show="shown" x-transition:enter="transition ease-out duration-500 delay-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="mt-6 text-lg text-indigo-200 max-w-xl mx-auto leading-relaxed">
                           One Day or Day One
                        </p>

                        {{-- THIS IS THE CHANGED PART --}}
                        <div x-show="shown" x-transition:enter="transition ease-out duration-500 delay-400" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="mt-12 flex items-center justify-center">
                            @auth
                                {{-- This button shows IF THE USER IS LOGGED IN --}}
                                <a href="{{ route('dashboard') }}" class="rounded-full bg-blue-600 px-8 py-4 text-lg font-bold text-white shadow-xl hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                                    TOS <span aria-hidden="true">&rarr;</span>
                                </a>
                            @else
                                {{-- This button shows IF THE USER IS A GUEST --}}
                                <a href="{{ route('register') }}" class="rounded-full bg-blue-600 px-8 py-4 text-lg font-bold text-white shadow-xl hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                                    TOS <span aria-hidden="true">&rarr;</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>