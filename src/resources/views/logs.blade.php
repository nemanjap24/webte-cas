@extends('layouts.app')

@section('title', __('messages.nav.logs'))

@section('content')
<div class="mx-auto max-w-6xl px-6 py-10">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">{{ __('messages.logs_title') }}</h1>
            <p class="mt-2 text-slate-400">{{ __('messages.logs_subtitle') }}</p>
        </div>
        <a href="{{ route('logs.export') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-5 py-2.5 font-semibold text-white transition hover:bg-white/20 border border-white/10">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            {{ __('messages.export_csv') }}
        </a>
    </div>
    
    <div class="mt-10 overflow-hidden rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-white/5 border-b border-white/10 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">{{ __('messages.time') }}</th>
                        <th class="px-6 py-4 font-semibold">Session</th>
                        <th class="px-6 py-4 font-semibold">Command</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($logs as $log)
                    <tr class="hover:bg-white/[0.02] transition">
                        <td class="whitespace-nowrap px-6 py-4 font-mono text-[11px] text-slate-400">
                            {{ $log->executed_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="rounded bg-slate-800 px-2 py-0.5 font-mono text-[10px] text-slate-400">
                                {{ Str::limit($log->session_token, 8, '') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <code class="text-cyan-400 text-[12px]">{{ Str::limit($log->command, 50) }}</code>
                        </td>
                        <td class="px-6 py-4">
                            @if($log->is_success)
                                <span class="inline-flex items-center gap-1.5 text-emerald-400">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                    {{ __('messages.status_success') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-red-400" title="{{ $log->error_message }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                                    {{ __('messages.status_error') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center italic text-slate-500">
                            {{ __('messages.no_logs') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>

<style>
    /* Custom tailwind-like styles for Laravel pagination */
    .pagination { @apply flex gap-1; }
    .page-item { @apply inline-block; }
    .page-link { @apply flex h-10 w-10 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-sm font-medium text-slate-400 transition hover:bg-white/10 hover:text-white; }
    .page-item.active .page-link { @apply border-cyan-400/50 bg-cyan-400/10 text-cyan-400; }
    .page-item.disabled .page-link { @apply opacity-30 cursor-not-allowed; }
</style>
@endsection
