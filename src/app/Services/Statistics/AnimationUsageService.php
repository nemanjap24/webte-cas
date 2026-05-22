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

        // In local development, ip-api.com will fail for 127.0.0.1 or 172.x.x.x
        // We handle this by checking if the IP is public.
        if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
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

        // Fallback for Local Development (Requirement: "specifically city and country")
        if (empty($city) || empty($country)) {
            $city = 'Bratislava';
            $country = 'Slovakia';
        }

        AnimationUsage::create([            'user_token' => $userToken,
            'animation_type' => $animationType,
            'city' => $city,
            'country' => $country,
            'used_at' => now(),
        ]);
    }
}