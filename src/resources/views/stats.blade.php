@extends('layouts.app')

@section('title', __('messages.nav.stats'))

@section('content')
<div class="mx-auto max-w-6xl px-6 py-10">
    <header>
        <h1 class="text-3xl font-bold text-white">{{ __('messages.stats_title') }}</h1>
        <p class="mt-2 text-slate-400">{{ __('messages.stats_subtitle') }}</p>
    </header>
    
    <div class="mt-10 grid gap-6 md:grid-cols-2">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-8 backdrop-blur-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-cyan-400/10 blur-3xl transition group-hover:bg-cyan-400/20"></div>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Inverted Pendulum</h3>
            <p class="mt-2 text-5xl font-black text-white">{{ $pendulum_count }}</p>
            <p class="mt-1 text-xs text-slate-400">Validated unique sessions</p>
        </div>
        
        <div class="rounded-2xl border border-white/10 bg-white/5 p-8 backdrop-blur-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-400/10 blur-3xl transition group-hover:bg-emerald-400/20"></div>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Ball & Beam</h3>
            <p class="mt-2 text-5xl font-black text-white">{{ $ball_count }}</p>
            <p class="mt-1 text-xs text-slate-400">Validated unique sessions</p>
        </div>
    </div>

    <div class="mt-12">
        <h2 class="text-xl font-bold text-white mb-6">Recent Activity Details</h2>
        <div class="overflow-hidden rounded-2xl border border-white/10 bg-white/5">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-white/5 border-b border-white/10 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Time</th>
                            <th class="px-6 py-4 font-semibold">Animation</th>
                            <th class="px-6 py-4 font-semibold">Location</th>
                            <th class="px-6 py-4 font-semibold">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($recent as $item)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="whitespace-nowrap px-6 py-4 text-slate-400">
                                {{ $item->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($item->animation_name === 'pendulum')
                                    <span class="rounded-full bg-cyan-400/10 px-2.5 py-0.5 text-[11px] font-bold text-cyan-400 uppercase">Pendulum</span>
                                @else
                                    <span class="rounded-full bg-emerald-400/10 px-2.5 py-0.5 text-[11px] font-bold text-emerald-400 uppercase">Ball & Beam</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-white">{{ $item->city }}</span>, <span class="text-slate-500">{{ $item->country }}</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-[11px] text-slate-500">
                                {{ $item->ip_address }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center italic text-slate-500">
                                No activity recorded yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
