<?php

return [
    'api_key' => env('CAS_API_KEY'),
    'executable_path' => env('CAS_EXECUTABLE_PATH', '/usr/bin/octave'),
    'slowdown_ms' => (int) env('CAS_SLOWDOWN_MS', 300),
    'stats_interval_minutes' => (int) env('STATS_INTERVAL_MINUTES', 10),
    'animation_count_interval_minutes' => env('ANIMATION_COUNT_INTERVAL_MINUTES', 10),
];