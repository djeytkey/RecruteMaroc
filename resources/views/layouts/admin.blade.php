<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - {{ $systemSettings?->system_name ?? config('app.name') }}</title>
    @if($systemSettings?->favicon_url)
    <link rel="icon" href="{{ $systemSettings->favicon_url }}" type="image/x-icon">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <header class="bg-slate-800 text-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('admin.users.index') }}" class="font-bold flex items-center gap-2">
                @if($systemSettings?->logo_url)
                    <img src="{{ $systemSettings->logo_url }}" alt="" class="h-8 object-contain">
                @endif
                Admin — {{ $systemSettings?->system_name ?? config('app.name') }}
            </a>
            <nav class="flex gap-4 text-sm">
                <a href="{{ route('admin.users.index') }}" class="hover:underline">Utilisateurs</a>
                <a href="{{ route('admin.companies.index') }}" class="hover:underline">Entreprises</a>
                <a href="{{ route('admin.offers.index') }}" class="hover:underline">Offres</a>
                <a href="{{ route('admin.rewards.index') }}" class="hover:underline">Récompenses</a>
                <a href="{{ route('admin.exports.index') }}" class="hover:underline">Exports</a>
                <a href="{{ route('admin.settings.index') }}" class="hover:underline">Paramètres</a>
                <a href="{{ route('home') }}" class="text-slate-300 hover:text-white">Site</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">@csrf<button type="submit" class="hover:underline">Déconnexion</button></form>
            </nav>
        </div>
    </header>
    <main class="max-w-7xl mx-auto px-4 py-6">
        @if(session('success'))<div class="mb-4 p-3 bg-emerald-100 text-emerald-800 rounded-lg">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg">{{ session('error') }}</div>@endif
        @yield('content')
    </main>
</body>
</html>
