@extends('layouts.app')

@section('title', __('messages.nav.home'))

@section('content')
<main class="text-slate-100">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-12 px-6 py-16 lg:px-10">
        <!-- Hero Section -->
        <section class="space-y-6">
            <span class="inline-block rounded-full border border-cyan-400/30 bg-cyan-400/10 px-4 py-1 text-xs font-bold tracking-widest text-cyan-400 uppercase">
                {{ __('messages.badge') }}
            </span>
            <h1 class="max-w-4xl text-5xl font-black leading-tight tracking-tight md:text-6xl">
                {!! str_replace('CAS', '<span class="text-cyan-400">CAS</span>', __('messages.title')) !!}
            </h1>
            <p class="max-w-2xl text-xl leading-relaxed text-slate-400">
                {{ __('messages.subtitle') }}
            </p>
            <div class="flex flex-wrap gap-4 pt-4">
                <a href="/console" class="rounded-xl bg-cyan-400 px-8 py-4 font-bold text-slate-950 shadow-lg shadow-cyan-400/20 transition hover:bg-cyan-300 hover:scale-105 active:scale-95">
                    {{ __('messages.ctaPrimary') }}
                </a>
                <a href="/docs" class="rounded-xl border border-white/10 bg-white/5 px-8 py-4 font-bold text-white backdrop-blur-sm transition hover:bg-white/10">
                    {{ __('messages.ctaSecondary') }}
                </a>
            </div>
        </section>

        <!-- Core Features Grid -->
        <section class="grid gap-6 md:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-8 transition hover:border-cyan-400/30">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-400/10 text-cyan-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold">{{ __('messages.cards.simulations.title') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-400">{{ __('messages.cards.simulations.text') }}</p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-8 transition hover:border-cyan-400/30">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-400/10 text-cyan-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold">{{ __('messages.cards.api.title') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-400">{{ __('messages.cards.api.text') }}</p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-8 transition hover:border-cyan-400/30">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-400/10 text-cyan-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold">{{ __('messages.cards.docs.title') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-400">{{ __('messages.cards.docs.text') }}</p>
                <a href="/api/docs/pdf" class="mt-4 inline-block text-xs font-bold uppercase tracking-widest text-cyan-400 hover:text-cyan-300">{{ __('messages.download_pdf') }} &rarr;</a>
            </div>
        </section>

        <!-- Technical Footer -->
        <footer class="flex items-center justify-between border-t border-white/10 pt-8 text-xs font-medium text-slate-500">
            <div class="flex gap-6">
                <span>Octave Engine v9.x</span>
                <span>Laravel Framework v13.x</span>
            </div>
            <div>
                © 2026 {{ __('messages.footer_text') }}
            </div>
        </footer>
    </div>
</main>
@endsection
