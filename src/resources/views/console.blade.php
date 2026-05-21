@extends('layouts.app')

@section('title', __('messages.console_title'))

@section('content')
<main class="min-h-screen bg-slate-950 px-6 py-10 text-slate-100">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-6">
        <header class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-3xl font-bold">{{ __('messages.console_title') }}</h1>
            <div class="rounded-lg border border-white/15 bg-white/5 p-1 text-sm">
                <a href="{{ route('lang.switch', 'sk') }}"
                   class="inline-block rounded-md px-3 py-1.5 {{ app()->getLocale() === 'sk' ? 'bg-white text-slate-900' : 'text-slate-200 hover:bg-white/10' }}">
                    SK
                </a>
                <a href="{{ route('lang.switch', 'en') }}"
                   class="inline-block rounded-md px-3 py-1.5 {{ app()->getLocale() === 'en' ? 'bg-white text-slate-900' : 'text-slate-200 hover:bg-white/10' }}">
                    EN
                </a>
            </div>
        </header>

        <p class="text-slate-300">{{ __('messages.console_subtitle') }}</p>

        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Console Form -->
            <form id="cas-form" class="space-y-3 rounded-2xl border border-white/10 bg-white/5 p-4">
                <div class="grid gap-3 md:grid-cols-2">
                    <label class="space-y-1 text-sm">
                        <span class="text-slate-300">{{ __('messages.sessionToken') }}</span>
                        <input type="text" id="session-token" value="team-session-01" class="w-full rounded-lg border border-white/15 bg-slate-900 px-3 py-2 outline-none ring-cyan-300 focus:ring" />
                    </label>
                    <label class="space-y-1 text-sm">
                        <span class="text-slate-300">{{ __('messages.apiKey') }}</span>
                        <input type="text" id="api-key" value="demo-token" class="w-full rounded-lg border border-white/15 bg-slate-900 px-3 py-2 outline-none ring-cyan-300 focus:ring" />
                    </label>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="loadSample('ball')" class="rounded-lg border border-cyan-300/30 bg-cyan-400/10 px-3 py-1.5 text-sm text-cyan-200 hover:bg-cyan-400/20">
                        {{ __('messages.loadBall') }}
                    </button>
                    <button type="button" onclick="loadSample('pendulum')" class="rounded-lg border border-cyan-300/30 bg-cyan-400/10 px-3 py-1.5 text-sm text-cyan-200 hover:bg-cyan-400/20">
                        {{ __('messages.loadPendulum') }}
                    </button>
                </div>

                <textarea id="script-input" class="h-80 w-full rounded-xl border border-white/15 bg-slate-900 p-3 font-mono text-sm outline-none ring-cyan-300 focus:ring"></textarea>

                <div class="flex flex-wrap gap-2">
                    <button type="submit" id="run-btn" class="rounded-lg bg-cyan-400 px-4 py-2 font-semibold text-slate-950 hover:bg-cyan-300 disabled:cursor-not-allowed disabled:opacity-60">
                        {{ __('messages.run') }}
                    </button>
                    <button type="button" onclick="document.getElementById('script-input').value = ''" class="rounded-lg border border-white/20 px-4 py-2 hover:bg-white/5">
                        {{ __('messages.clearScript') }}
                    </button>
                </div>

                <div id="error-message" class="hidden rounded-lg border border-red-400/40 bg-red-500/10 px-3 py-2 text-sm text-red-200"></div>
            </form>

            <!-- Console Output -->
            <section class="space-y-4 rounded-2xl border border-white/10 bg-white/5 p-4">
                <div>
                    <h2 class="mb-2 font-semibold text-cyan-200">{{ __('messages.lastBatch') }}</h2>
                    <div id="last-batch-output" class="max-h-44 space-y-2 overflow-auto rounded-xl bg-slate-900 p-3 font-mono text-sm">
                        <p class="text-slate-400">No batch executed yet.</p>
                    </div>
                </div>

                <!-- We will add the visual charts/animations here later -->

            </section>
        </div>

        <a href="/" class="text-cyan-300 hover:underline">← {{ __('messages.back') }}</a>
    </div>
</main>

<script>
    const ballSample = `% Ball & Beam quick sample\nr = 0.25\na = 1+1\na+2\nr*4`;
    const pendulumSample = `% Inverted pendulum quick sample\nx = 0.2\ntheta = 0\nx + 0.3\ntheta + 1`;

    function loadSample(type) {
        const input = document.getElementById('script-input');
        if (type === 'ball') {
            input.value = ballSample;
        } else {
            input.value = pendulumSample;
        }
    }

    // Default load ball sample on start
    document.addEventListener('DOMContentLoaded', () => {
        loadSample('ball');
    });

    document.getElementById('cas-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const runBtn = document.getElementById('run-btn');
        const errorDiv = document.getElementById('error-message');
        const lastBatchDiv = document.getElementById('last-batch-output');

        const script = document.getElementById('script-input').value;
        const sessionToken = document.getElementById('session-token').value;
        // In reality, API Key will be handled via config, but we pass it as instructed for now
        // Or we use Sanctum. Polo will define this in POST /api/execute

        runBtn.disabled = true;
        runBtn.innerText = 'Running...';
        errorDiv.classList.add('hidden');

        try {
            const response = await fetch('/api/cas/execute', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    command: script,
                    session_token: sessionToken
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || data.error || 'Server error');
            }

            lastBatchDiv.innerHTML = `
                <div class="rounded border border-white/10 p-2">
                    <p class="text-slate-400 whitespace-pre-wrap">&gt; ${script}</p>
                    <p class="text-emerald-200 whitespace-pre-wrap">${data.output}</p>
                </div>
            `;

        } catch (err) {
            errorDiv.innerText = err.message;
            errorDiv.classList.remove('hidden');
            lastBatchDiv.innerHTML = `
                <div class="rounded border border-red-500/30 p-2 bg-red-500/10">
                    <p class="text-red-200 whitespace-pre-wrap">Error: ${err.message}</p>
                </div>
            `;
        } finally {
            runBtn.disabled = false;
            runBtn.innerText = '{{ __('messages.run') }}';
        }
    });
</script>
@endsection
