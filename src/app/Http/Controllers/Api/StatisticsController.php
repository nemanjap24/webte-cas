<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnimationUsage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $stats = AnimationUsage::query()
            ->selectRaw('animation_type, COUNT(*) as usage_count')
            ->groupBy('animation_type')
            ->orderBy('animation_type')
            ->get();

        $details = AnimationUsage::query()
            ->orderByDesc('used_at')
            ->get(['user_token', 'animation_type', 'city', 'country', 'used_at']);

        return response()->json([
            'summary' => $stats,
            'details' => $details,
            'interval_minutes' => config('statistics.animation_count_interval_minutes'),
        ]);
    }
}