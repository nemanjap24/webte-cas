<?php

namespace App\Services\Statistics;

use App\Models\AnimationUsage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnimationUsageService
{
    public function record(string $userToken, string $animationType, ?string $ip = null): void
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

        $city = null;
        $country = null;

        if ($ip && $ip !== '127.0.0.1') {
            try {
                $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=status,country,city");

                if ($response->successful() && $response->json('status') === 'success') {
                    $city = $response->json('city');
                    $country = $response->json('country');
                }
            } catch (\Exception $e) {
                Log::warning("Failed to fetch geolocation for IP {$ip}: " . $e->getMessage());
            }
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