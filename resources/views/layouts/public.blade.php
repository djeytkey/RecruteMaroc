<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Emploi Maroc - Offres d\'emploi') - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <header class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl text-emerald-600">
                    <span class="bg-emerald-500 text-white px-2 py-0.5 rounded">Emploi</span>
                    <span class="text-slate-700">Maroc</span>
                </a>
                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('job-offers.index') }}" class="text-slate-600 hover:text-emerald-600 font-medium">Offres d'emploi</a>
                    @auth
                        @if(auth()->user()->isCandidate())
                            <a href="{{ route('candidat.dashboard') }}" class="text-slate-600 hover:text-emerald-600 font-medium">Mon espace</a>
                        @elseif(auth()->user()->isRecruiter())
                            <a href="{{ route('recruteur.dashboard') }}" class="text-slate-600 hover:text-emerald-600 font-medium">Espace recruteur</a>
                        @elseif(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-slate-600 hover:text-emerald-600 font-medium">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-slate-600 hover:text-red-600 font-medium">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('register') }}?type=candidat" class="text-slate-600 hover:text-emerald-600 font-medium">Candidat</a>
                        <a href="{{ route('register') }}?type=recruteur" class="text-slate-600 hover:text-emerald-600 font-medium">Recruteur</a>
                        <a href="{{ route('login') }}" class="bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-lg font-medium">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium">Inscription</a>
                    @endauth
                </nav>
            </div>
            <div class="pb-4">
                <form action="{{ route('job-offers.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2 max-w-3xl">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Métier, mots-clés..." class="flex-1 rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <input type="text" name="location" value="{{ request('location') }}" placeholder="Ville ou région" class="sm:w-48 rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg font-medium">Rechercher</button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-slate-800 text-slate-300 mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <span class="font-semibold text-white">Emploi Maroc</span>
                <div class="flex gap-6">
                    <a href="{{ route('job-offers.index') }}" class="hover:text-white">Offres</a>
                    <a href="{{ route('contact') }}" class="hover:text-white">Nous contacter</a>
                </div>
            </div>
            <p class="mt-4 text-sm text-slate-400 text-center md:text-left">© {{ date('Y') }} Plateforme de recrutement Maroc. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
