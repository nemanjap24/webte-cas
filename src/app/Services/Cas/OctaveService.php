<?php

namespace App\Services\Cas;

use Symfony\Component\Process\Process;

class OctaveService
{
    public function execute(string $command, string $sessionToken): array
    {
        $sessionPath = storage_path("app/cas_sessions/{$sessionToken}.mat");
        $tempScriptPath = storage_path("app/cas_sessions/{$sessionToken}_exec.m");

        $script = $this->buildFullScript($command, $sessionPath);
        file_put_contents($tempScriptPath, $script);

        $process = new Process([
            config('cas.executable_path'),
            '--quiet',
            '--no-gui',
            $tempScriptPath,
        ]);

        $process->setTimeout(15);
        $process->run();

        // Cleanup temp script
        if (file_exists($tempScriptPath)) {
            unlink($tempScriptPath);
        }

        usleep(config('cas.slowdown_ms') * 1000);

        if (! $process->isSuccessful()) {
            return [
                'success' => false,
                'output' => null,
                'error' => trim($process->getErrorOutput() ?: $process->getOutput()),
            ];
        }

        return [
            'success' => true,
            'output' => trim($process->getOutput()),
            'error' => null,
        ];
    }

    private function buildFullScript(string $command, string $sessionPath): string
    {
        $script = [];
        $script[] = "warning('off', 'all');"; // Suppress warnings for cleaner output
        
        if (file_exists($sessionPath)) {
            $script[] = "load('{$sessionPath}');";
        }

        $script[] = $command;
        $script[] = "save('-binary', '{$sessionPath}');";

        return implode("\n", $script);
    }
}