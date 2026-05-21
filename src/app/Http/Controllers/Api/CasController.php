<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CasLog;
use App\Models\CasSession;
use App\Services\Cas\OctaveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CasController extends Controller
{
    public function execute(Request $request, OctaveService $octaveService): JsonResponse
    {
        $validated = $request->validate([
            'command' => ['required', 'string'],
            'session_token' => ['required', 'string', 'max:255'],
        ]);

        $result = $octaveService->execute(
            $validated['command'],
            $validated['session_token']
        );

        CasLog::create([
            'session_token' => $validated['session_token'],
            'command' => $validated['command'],
            'output' => $result['output'],
            'is_success' => $result['success'],
            'error_message' => $result['error'],
            'executed_at' => now(),
        ]);

        return response()->json([
            'success' => $result['success'],
            'output' => $result['output'],
            'error' => $result['error'],
            'session_token' => $validated['session_token'],
        ], $result['success'] ? 200 : 422);
    }
}