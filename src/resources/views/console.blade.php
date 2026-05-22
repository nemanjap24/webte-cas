@extends('layouts.app')

@section('title', __('messages.console_title'))

@section('content')
<main class="px-6 py-10 text-slate-100">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-6">
        <h1 class="text-3xl font-bold">{{ __('messages.console_title') }}</h1>

        <p class="text-slate-300">{{ __('messages.console_subtitle') }}</p>

        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Left Side: Input -->
            <div class="flex flex-col gap-3">
                <div class="flex items-center justify-between h-9">
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="window.loadCasSample('ball')" class="rounded-lg border border-cyan-300/30 bg-cyan-400/10 px-3 py-1 text-xs text-cyan-200 hover:bg-cyan-400/20">
                            {{ __('messages.loadBall') }}
                        </button>
                        <button type="button" onclick="window.loadCasSample('pendulum')" class="rounded-lg border border-cyan-300/30 bg-cyan-400/10 px-3 py-1 text-xs text-cyan-200 hover:bg-cyan-400/20">
                            {{ __('messages.loadPendulum') }}
                        </button>
                    </div>
                </div>

                <form id="cas-form" 
                      data-session-token="{{ $sessionToken }}" 
                      data-api-key="{{ $apiKey }}"
                      data-error-server="{{ __('messages.console_errors.server') }}"
                      data-error-validation="{{ __('messages.console_errors.validation') }}"
                      data-error-undefined-variable="{{ __('messages.console_errors.undefined_variable') }}"
                      data-error-parse="{{ __('messages.console_errors.parse') }}"
                      data-error-generic="{{ __('messages.console_errors.generic') }}"
                      class="flex flex-col gap-3">
                    
                    <div id="editor-container" class="h-80 w-full rounded-xl border border-white/15 bg-slate-900 overflow-hidden ring-cyan-300 focus-within:ring"></div>
                    <!-- Hidden textarea for form compatibility if needed, but we'll use CM directly -->
                    <textarea id="script-input" class="hidden"></textarea>

                    <div class="flex flex-wrap gap-2">
                        <button type="submit" 
                                id="run-btn" 
                                data-label-run="{{ __('messages.run') }}" 
                                data-label-running="{{ __('messages.running') }}"
                                class="rounded-lg bg-cyan-400 px-4 py-2 font-semibold text-slate-950 hover:bg-cyan-300 disabled:cursor-not-allowed disabled:opacity-60">
                            {{ __('messages.run') }}
                        </button>
                        <button type="button" id="clear-btn" class="rounded-lg border border-white/20 px-4 py-2 hover:bg-white/5">
                            {{ __('messages.clearScript') }}
                        </button>
                    </div>
                    
                    <div id="error-message" class="hidden rounded-lg border border-red-400/40 bg-red-500/10 px-3 py-2 text-sm text-red-200"></div>
                </form>
            </div>

            <!-- Right Side: Output -->
            <div class="flex flex-col gap-3">
                <div class="flex items-center justify-between h-9">
                    <h2 class="font-semibold text-cyan-200">{{ __('messages.lastBatch') }}</h2>
                    <span class="text-[10px] text-slate-500 font-mono">Session: {{ substr($sessionToken, 0, 8) }}...</span>
                </div>

                <div id="last-batch-output" class="h-80 w-full space-y-2 overflow-y-auto rounded-xl bg-slate-900 p-3 font-mono text-sm border border-white/15">
                    <p class="text-slate-400 italic">{{ __('messages.noBatch') }}</p>
                </div>

                <!-- Spacer to align with the buttons on the left -->
                <div class="h-[40px] invisible"></div>
            </div>
        </div>

        <a href="/" class="text-cyan-300 hover:underline">← {{ __('messages.back') }}</a>
    </div>
</main>

<style>
    /* CodeMirror specific styling to match our theme */
    .cm-editor { height: 100%; }
    .cm-scroller { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important; }
</style>
@endsection
