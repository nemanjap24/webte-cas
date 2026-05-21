<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnimationStatistic;
use App\Models\CasLog;
use App\Services\Simulation\SimulationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SimulationController extends Controller
{
    public function invertedPendulum(Request $request, SimulationService $simulationService): JsonResponse
    {
        $validated = $request->validate([
            'target_position' => ['nullable', 'numeric'],
            'session_token' => ['nullable', 'string', 'max:255'],
        ]);

        $targetPosition = (float) ($validated['target_position'] ?? 0.2);
        $sessionToken = $validated['session_token'] ?? null;

        $result = $simulationService->runInvertedPendulum($targetPosition);

        $this->recordStatistic('pendulum', $sessionToken, $request);

        CasLog::create([
            'session_token' => $sessionToken,
            'command' => 'SIMULATION: inverted-pendulum target_position=' . $targetPosition,
            'output' => $result['success'] ? json_encode($result['data']) : null,
            'is_success' => $result['success'],
            'error_message' => $result['error'],
            'executed_at' => now(),
        ]);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function ballBeam(Request $request, SimulationService $simulationService): JsonResponse
    {
        $validated = $request->validate([
            'target_position' => ['nullable', 'numeric'],
            'session_token' => ['nullable', 'string', 'max:255'],
        ]);

        $targetPosition = (float) ($validated['target_position'] ?? 0.25);
        $sessionToken = $validated['session_token'] ?? null;

        $result = $simulationService->runBallBeam($targetPosition);

        $this->recordStatistic('ball', $sessionToken, $request);

        CasLog::create([
            'session_token' => $sessionToken,
            'command' => 'SIMULATION: ball-beam target_position=' . $targetPosition,
            'output' => $result['success'] ? json_encode($result['data']) : null,
            'is_success' => $result['success'],
            'error_message' => $result['error'],
            'executed_at' => now(),
        ]);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    private function recordStatistic(string $name, ?string $token, Request $request): void
    {
        if (!$token) return;

        $cooldownMinutes = config('cas.stats_interval_minutes', 10);
        
        $lastStat = AnimationStatistic::where('session_token', $token)
            ->where('animation_name', $name)
            ->orderByDesc('created_at')
            ->first();

        if ($lastStat && Carbon::parse($lastStat->created_at)->diffInMinutes(now()) < $cooldownMinutes) {
            return;
        }

        // Mock GeoIP data - in a real app, use a service or library like torann/geoip
        $ip = $request->ip();
        $mockLocations = [
            '127.0.0.1' => ['city' => 'Bratislava', 'country' => 'Slovakia'],
            '::1' => ['city' => 'Bratislava', 'country' => 'Slovakia'],
        ];
        
        $location = $mockLocations[$ip] ?? ['city' => 'Unknown', 'country' => 'Unknown'];

        AnimationStatistic::create([
            'animation_name' => $name,
            'session_token' => $token,
            'ip_address' => $ip,
            'city' => $location['city'],
            'country' => $location['country'],
            'created_at' => now(),
        ]);
    }
}
