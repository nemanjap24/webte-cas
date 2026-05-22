<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CasLog;
use App\Models\CasSession;
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
            'reset' => ['nullable', 'boolean'],
        ]);

        $sessionToken = $validated['session_token'] ?? $request->cookie('cas_session_token');
        $session = null;
        $initialState = null;

        if ($sessionToken) {
            $session = CasSession::firstOrCreate(['session_token' => $sessionToken]);
            if (empty($validated['reset'])) {
                $initialState = $session->sim_state['pendulum'] ?? null;
            } else {
                $currentSimState = $session->sim_state ?? [];
                $currentSimState['pendulum'] = null;
                $session->update(['sim_state' => $currentSimState]);
            }
        }

        $targetPosition = (float) ($validated['target_position'] ?? 0.2);
        $result = $simulationService->runInvertedPendulum($targetPosition, $initialState);

        if ($result['success']) {
            if ($session) {
                $newState = $session->sim_state ?? [];
                $newState['pendulum'] = $result['data']['final_state'];
                $session->update(['sim_state' => $newState]);
            }

            if ($sessionToken) {
                $animationUsageService->record($sessionToken, 'inverted-pendulum', $request->ip());
            }
        }

        CasLog::create([
            'session_token' => $sessionToken,
            'command' => 'SIMULATION: inverted-pendulum target_position=' . $targetPosition . ($initialState ? ' (continued)' : ''),
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
            'reset' => ['nullable', 'boolean'],
        ]);

        $sessionToken = $validated['session_token'] ?? $request->cookie('cas_session_token');
        $session = null;
        $initialState = null;

        if ($sessionToken) {
            $session = CasSession::firstOrCreate(['session_token' => $sessionToken]);
            if (empty($validated['reset'])) {
                $initialState = $session->sim_state['ball'] ?? null;
            } else {
                $currentSimState = $session->sim_state ?? [];
                $currentSimState['ball'] = null;
                $session->update(['sim_state' => $currentSimState]);
            }
        }

        $targetPosition = (float) ($validated['target_position'] ?? 0.25);
        $result = $simulationService->runBallBeam($targetPosition, $initialState);

        if ($result['success']) {
            if ($session) {
                $newState = $session->sim_state ?? [];
                $newState['ball'] = $result['data']['final_state'];
                $session->update(['sim_state' => $newState]);
            }

            if ($sessionToken) {
                $animationUsageService->record($sessionToken, 'ball-beam', $request->ip());
            }
        }

        CasLog::create([
            'session_token' => $sessionToken,
            'command' => 'SIMULATION: ball-beam target_position=' . $targetPosition . ($initialState ? ' (continued)' : ''),
            'output' => $result['success'] ? json_encode($result['data']) : null,
            'is_success' => $result['success'],
            'error_message' => $result['error'],
            'executed_at' => now(),
        ]);

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}
