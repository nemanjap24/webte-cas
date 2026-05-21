<?php

namespace App\Services\Statistics;

use App\Models\AnimationUsage;

class AnimationUsageService
{
    public function record(string $userToken, string $animationType, ?string $city = null, ?string $country = null): void
    {
        $minutes = (int) config('statistics.animation_count_interval_minutes', 10);

        $recent = AnimationUsage::query()
            ->where('user_token', $userToken)
            ->where('animation_type', $animationType)
            ->where('used_at', '>=', now()->subMinutes($minutes))
            ->exists();

        if ($recent) {
            return;
        }

        AnimationUsage::create([
            'user_token' => $userToken,
            'animation_type' => $animationType,
            'city' => $city,
            'country' => $country,
            'used_at' => now(),
        ]);
    }
}