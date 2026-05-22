@extends('layouts.app')

@section('title', __('messages.nav.animations'))

@section('content')
<main id="simulation-container" data-api-key="{{ $apiKey }}" class="mx-auto max-w-6xl px-6 py-10 text-slate-100">
    <div class="flex flex-col gap-8">
        <header>
            <h1 class="text-3xl font-bold">{{ __('messages.animations_title') }}</h1>
            <p class="mt-2 text-slate-400">{{ __('messages.animations_subtitle') }}</p>
        </header>

        <div class="grid gap-8 lg:grid-cols-3">
            <!-- Controls Sidebar -->
            <aside class="flex flex-col gap-6 rounded-2xl border border-white/10 bg-white/5 p-6 h-fit">
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">{{ __('messages.select_system') }}</label>
                    <select id="system-selector" class="w-full rounded-xl border border-white/15 bg-slate-900 p-3 outline-none focus:ring-2 focus:ring-cyan-400 transition">
                        <option value="pendulum">Inverted Pendulum</option>
                        <option value="ball">Ball & Beam</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">{{ __('messages.target_position') }}</label>
                    <input type="range" id="target-slider" min="-0.5" max="0.5" step="0.01" value="0.2" class="w-full accent-cyan-400">
                    <div class="mt-1 flex justify-between text-[10px] text-slate-500 font-mono">
                        <span>-0.5m</span>
                        <span id="target-value" class="text-cyan-300 font-bold">0.20m</span>
                        <span>0.5m</span>
                    </div>
                </div>

                <button id="start-btn" class="w-full rounded-xl bg-cyan-400 py-3 font-bold text-slate-950 hover:bg-cyan-300 transition disabled:opacity-50">
                    {{ __('messages.run_simulation') }}
                </button>

                <button id="reset-btn" class="w-full rounded-xl border border-white/10 bg-white/5 py-3 font-bold text-white hover:bg-white/10 transition disabled:opacity-50">
                    {{ __('messages.reset_state') }}
                </button>

                <div id="status-indicator" class="hidden rounded-lg border border-cyan-400/20 bg-cyan-400/10 px-3 py-2 text-xs text-cyan-200 animate-pulse">
                    {{ __('messages.computing') }}
                </div>
            </aside>

            <!-- Visualization Area -->
            <div class="lg:col-span-2 flex flex-col gap-8">
                <!-- Canvas Animation -->
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-slate-900 shadow-2xl">
                    <div class="absolute top-4 left-4 z-10 rounded-md bg-slate-950/60 px-3 py-1 text-[10px] font-mono text-cyan-300 backdrop-blur-sm">
                        2D ANIMATION
                    </div>
                    <canvas id="sim-canvas" width="800" height="400" class="w-full h-auto"></canvas>
                </div>

                <!-- Charts -->
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Real-time Metrics</h3>
                        <div id="chart-legend" class="flex gap-4 text-[10px] font-mono">
                            <span class="flex items-center gap-1.5 text-cyan-400"><span class="h-2 w-2 rounded-full bg-cyan-400"></span> POSITION</span>
                            <span class="flex items-center gap-1.5 text-emerald-400"><span class="h-2 w-2 rounded-full bg-emerald-400"></span> ANGLE</span>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="sim-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Load Libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('sim-canvas');
    const ctx = canvas.getContext('2d');
    const systemSelector = document.getElementById('system-selector');
    const targetSlider = document.getElementById('target-slider');
    const targetValue = document.getElementById('target-value');
    const startBtn = document.getElementById('start-btn');
    const resetBtn = document.getElementById('reset-btn');
    const status = document.getElementById('status-indicator');
    const container = document.getElementById('simulation-container');

    let isRunning = false;
    let animationId = null;

    // Chart.js Setup
    const chartCtx = document.getElementById('sim-chart').getContext('2d');
    const chart = new Chart(chartCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Position',
                    data: [],
                    borderColor: '#22d3ee',
                    backgroundColor: 'rgba(34, 211, 238, 0.1)',
                    borderWidth: 2,
                    pointRadius: 0,
                    tension: 0.1,
                    fill: true
                },
                {
                    label: 'Angle',
                    data: [],
                    borderColor: '#34d399',
                    borderWidth: 1,
                    pointRadius: 0,
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: {
                x: { display: false },
                y: {
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: { color: '#64748b', font: { size: 10 } }
                }
            },
            plugins: { legend: { display: false } }
        }
    });

    targetSlider.addEventListener('input', (e) => {
        targetValue.textContent = parseFloat(e.target.value).toFixed(2) + 'm';
    });

    resetBtn.addEventListener('click', async () => {
        if (isRunning) return;
        const system = systemSelector.value;
        const apiKey = container.dataset.apiKey;

        resetBtn.disabled = true;
        try {
            const endpoint = system === 'pendulum' ? '/api/simulations/inverted-pendulum' : '/api/simulations/ball-beam';
            await fetch(endpoint, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-API-KEY': apiKey
                },
                body: JSON.stringify({ 
                    reset: true,
                    target_position: system === 'pendulum' ? 0 : 0
                })
            });
            
            draw(system, 0, 0);
            chart.data.labels = [];
            chart.data.datasets.forEach(d => d.data = []);
            chart.update();
        } catch (err) {
            console.error(err);
        } finally {
            resetBtn.disabled = false;
        }
    });

    systemSelector.addEventListener('change', () => {
        const type = systemSelector.value;
        cancelAnimationFrame(animationId);
        isRunning = false;
        startBtn.disabled = false;
        
        draw(type, 0, 0);
        
        chart.data.labels = [];
        chart.data.datasets.forEach(d => d.data = []);
        chart.update();
    });

    async function runSimulation() {
        if (isRunning) return;
        
        const apiKey = container.dataset.apiKey;
        const system = systemSelector.value;
        const target = targetSlider.value;

        isRunning = true;
        startBtn.disabled = true;
        status.classList.remove('hidden');

        try {
            const endpoint = system === 'pendulum' ? '/api/simulations/inverted-pendulum' : '/api/simulations/ball-beam';
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-API-KEY': apiKey
                },
                body: JSON.stringify({ 
                    target_position: target
                })
            });

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`Server returned ${response.status}: ${errorText.substring(0, 100)}`);
            }

            const result = await response.json();
            if (!result.success) throw new Error(result.error);

            animateResults(system, result.data);
        } catch (err) {
            console.error(err);
            alert('Simulation Error: ' + err.message);
            isRunning = false;
            startBtn.disabled = false;
        } finally {
            status.classList.add('hidden');
        }
    }

    function animateResults(type, data) {
        const time = data.time;
        const posKey = type === 'pendulum' ? 'cart_position' : 'ball_position';
        const angKey = type === 'pendulum' ? 'pendulum_angle' : 'beam_angle';

        const positions = data[posKey];
        const angles = data[angKey];

        let frame = 0;
        const totalFrames = time.length;

        chart.data.labels = [];
        chart.data.datasets.forEach(d => d.data = []);

        function step() {
            if (frame >= totalFrames) {
                isRunning = false;
                startBtn.disabled = false;
                return;
            }

            const currentPos = positions[frame];
            const currentAng = angles[frame];

            chart.data.labels.push(time[frame].toFixed(2));
            chart.data.datasets[0].data.push(currentPos);
            chart.data.datasets[1].data.push(currentAng);

            chart.update('none');

            draw(type, currentPos, currentAng);

            frame++;
            animationId = requestAnimationFrame(step);
        }

        cancelAnimationFrame(animationId);
        step();
    }

    function draw(type, pos, ang) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const scale = 200;

        if (type === 'pendulum') {
            const cartW = 100, cartH = 50;
            const cartX = centerX + (pos * scale) - (cartW / 2);
            const cartY = centerY + 50;

            ctx.fillStyle = '#334155';
            ctx.fillRect(cartX, cartY, cartW, cartH);
            ctx.fillStyle = '#1e293b';
            ctx.beginPath();
            ctx.arc(cartX + 20, cartY + cartH, 10, 0, Math.PI * 2);
            ctx.arc(cartX + 80, cartY + cartH, 10, 0, Math.PI * 2);
            ctx.fill();

            const poleLen = 150;
            const poleX = cartX + (cartW / 2);
            const poleY = cartY;
            const endX = poleX + poleLen * Math.sin(ang);
            const endY = poleY - poleLen * Math.cos(ang);

            ctx.strokeStyle = '#22d3ee';
            ctx.lineWidth = 6;
            ctx.lineCap = 'round';
            ctx.beginPath();
            ctx.moveTo(poleX, poleY);
            ctx.lineTo(endX, endY);
            ctx.stroke();
            ctx.fillStyle = '#22d3ee';
            ctx.beginPath();
            ctx.arc(endX, endY, 12, 0, Math.PI * 2);
            ctx.fill();
        } else {
            const beamLen = 600;
            ctx.save();
            ctx.translate(centerX, centerY);
            ctx.rotate(ang);
            ctx.fillStyle = '#334155';
            ctx.fillRect(-beamLen/2, -5, beamLen, 10);
            const ballX = pos * scale;
            ctx.fillStyle = '#34d399';
            ctx.beginPath();
            ctx.arc(ballX, -20, 15, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();
            ctx.fillStyle = '#1e293b';
            ctx.beginPath();
            ctx.arc(centerX, centerY, 8, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    startBtn.addEventListener('click', runSimulation);
    draw('pendulum', 0, 0);
});
</script>

<style>
#sim-canvas {
    background: radial-gradient(circle at 50% 50%, #1e293b 0%, #0f172a 100%);
}
</style>
@endsection
