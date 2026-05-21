<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CasLog;
use App\Services\Simulation\SimulationService;
use App\Services\Statistics\AnimationUsageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SimulationController extends Controller
{
    public function invertedPendulum(
        Request $request,
        SimulationService $simulationService,
        AnimationUsageService $animationUsageService
    ): JsonResponse {
        $validated = $request->validate([
            'target_position' => ['nullable', 'numeric'],
            'session_token' => ['nullable', 'string', 'max:255'],
        ]);

        $targetPosition = (float) ($validated['target_position'] ?? 0.2);

        $result = $simulationService->runInvertedPendulum($targetPosition);

        if ($result['success']) {
            $userToken = $request->cookie('webte-cas-session');

            if ($userToken) {
                $animationUsageService->record(
                    $userToken,
                    'inverted-pendulum',
                    null,
                    null
                );
            }
        }

        CasLog::create([
            'session_token' => $validated['session_token'] ?? $request->cookie('webte-cas-session'),
            'command' => 'SIMULATION: inverted-pendulum target_position=' . $targetPosition,
            'output' => $result['success'] ? json_encode($result['data']) : null,
            'is_success' => $result['success'],
            'error_message' => $result['error'],
            'executed_at' => now(),
        ]);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function ballBeam(
        Request $request,
        SimulationService $simulationService,
        AnimationUsageService $animationUsageService
    ): JsonResponse {
        $validated = $request->validate([
            'target_position' => ['nullable', 'numeric'],
            'session_token' => ['nullable', 'string', 'max:255'],
        ]);

        $targetPosition = (float) ($validated['target_position'] ?? 0.25);

        $result = $simulationService->runBallBeam($targetPosition);

        if ($result['success']) {
            $userToken = $request->cookie('webte-cas-session');

            if ($userToken) {
                $animationUsageService->record(
                    $userToken,
                    'ball-beam',
                    null,
                    null
                );
            }
        }

        CasLog::create([
            'session_token' => $validated['session_token'] ?? $request->cookie('webte-cas-session'),
            'command' => 'SIMULATION: ball-beam target_position=' . $targetPosition,
            'output' => $result['success'] ? json_encode($result['data']) : null,
            'is_success' => $result['success'],
            'error_message' => $result['error'],
            'executed_at' => now(),
        ]);

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}