<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Webte CAS')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-200 antialiased min-h-screen">
    <nav class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur-md">
        <div class="mx-auto max-w-6xl px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-8">
                    <a href="/" class="text-xl font-bold tracking-tighter text-white">WEBTE<span class="text-cyan-400">CAS</span></a>
                    <!-- Desktop Nav -->
                    <div class="hidden md:flex items-center gap-5 text-sm font-medium text-slate-400">
                        <a href="/" class="hover:text-white transition {{ request()->is('/') ? 'text-white' : '' }}">{{ __('messages.nav.home') }}</a>
                        <a href="/console" class="hover:text-white transition {{ request()->is('console') ? 'text-white' : '' }}">{{ __('messages.nav.console') }}</a>
                        <a href="/animations" class="hover:text-white transition {{ request()->is('animations') ? 'text-white' : '' }}">{{ __('messages.nav.animations') }}</a>
                        <a href="/logs" class="hover:text-white transition {{ request()->is('logs') ? 'text-white' : '' }}">{{ __('messages.nav.logs') }}</a>
                        <a href="/stats" class="hover:text-white transition {{ request()->is('stats') ? 'text-white' : '' }}">{{ __('messages.nav.stats') }}</a>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Language Switcher (Desktop/Mobile always visible) -->
                    <div class="rounded-lg border border-white/15 bg-white/5 p-1 text-[11px] font-bold uppercase tracking-wider">
                        <a href="{{ route('lang.switch', 'sk') }}" 
                           class="inline-block rounded px-2 py-1 {{ app()->getLocale() === 'sk' ? 'bg-white text-slate-900' : 'text-slate-400 hover:text-white' }}">
                            SK
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" 
                           class="inline-block rounded px-2 py-1 {{ app()->getLocale() === 'en' ? 'bg-white text-slate-900' : 'text-slate-400 hover:text-white' }}">
                            EN
                        </a>
                    </div>

                    <!-- Hamburger Button (Mobile Only) -->
                    <button id="mobile-menu-btn" class="md:hidden text-slate-400 hover:text-white focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Nav Dropdown -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-white/10 bg-slate-900 px-6 py-4 space-y-3 shadow-2xl">
            <a href="/" class="block text-sm font-medium {{ request()->is('/') ? 'text-cyan-400' : 'text-slate-400' }}">{{ __('messages.nav.home') }}</a>
            <a href="/console" class="block text-sm font-medium {{ request()->is('console') ? 'text-cyan-400' : 'text-slate-400' }}">{{ __('messages.nav.console') }}</a>
            <a href="/animations" class="block text-sm font-medium {{ request()->is('animations') ? 'text-cyan-400' : 'text-slate-400' }}">{{ __('messages.nav.animations') }}</a>
            <a href="/logs" class="block text-sm font-medium {{ request()->is('logs') ? 'text-cyan-400' : 'text-slate-400' }}">{{ __('messages.nav.logs') }}</a>
            <a href="/stats" class="block text-sm font-medium {{ request()->is('stats') ? 'text-cyan-400' : 'text-slate-400' }}">{{ __('messages.nav.stats') }}</a>
        </div>
    </nav>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            const isHidden = menu.classList.contains('hidden');
            
            if (isHidden) {
                menu.classList.remove('hidden');
                icon.setAttribute('d', 'M6 18L18 6M6 6l12 12');
            } else {
                menu.classList.add('hidden');
                icon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            }
        });
    </script>

    @yield('content')
</body>
</html>
