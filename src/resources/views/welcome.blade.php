@extends('layouts.app')

@section('title', __('messages.title'))

@section('content')
<main class="text-slate-100">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-10 px-6 py-12 lg:px-10">
        <section class="space-y-5">
            <span class="inline-block rounded-full border border-cyan-400/40 bg-cyan-500/10 px-4 py-1 text-xs font-semibold tracking-wide text-cyan-200">
                {{ __('messages.badge') }}
            </span>
            <h1 class="max-w-4xl text-4xl font-bold leading-tight md:text-5xl">{{ __('messages.title') }}</h1>
            <p class="max-w-3xl text-lg text-slate-300">{{ __('messages.subtitle') }}</p>
            <div class="flex flex-wrap gap-3">
                <a href="/console" class="rounded-xl bg-cyan-400 px-5 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">
                    {{ __('messages.ctaPrimary') }}
                </a>
                <a href="https://ctms.engin.umich.edu/CTMS/index.php?example=BallBeam&section=ControlStateSpace" target="_blank" rel="noreferrer" class="rounded-xl border border-white/20 px-5 py-3 font-semibold text-slate-100 transition hover:bg-white/10">
                    {{ __('messages.ctaSecondary') }}
                </a>
            </div>
        </section>

        <section class="space-y-4">
            <h2 class="text-xl font-semibold text-cyan-200">{{ __('messages.sectionsTitle') }}</h2>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach (__('messages.cards') as $card)
                    <article class="rounded-2xl border border-white/10 bg-white/5 p-5 shadow-lg shadow-cyan-900/10">
                        <h3 class="mb-2 text-lg font-semibold">{{ $card['title'] }}</h3>
                        <p class="text-sm leading-6 text-slate-300">{{ $card['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <p class="rounded-xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200">
            {{ __('messages.footer') }}
        </p>
    </div>
</main>
@endsection
