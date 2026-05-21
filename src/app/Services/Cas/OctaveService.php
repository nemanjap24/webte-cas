<?php

namespace App\Services\Cas;

use Symfony\Component\Process\Process;

class OctaveService
{
    public function execute(string $command, ?string $existingContext = null): array
    {
        $script = $this->buildScript($command, $existingContext);

        $process = new Process([
            config('cas.executable_path'),
            '--quiet',
            '--eval',
            $script,
        ]);

        $process->setTimeout(15);
        $process->run();

        usleep(config('cas.slowdown_ms') * 1000);

        if (! $process->isSuccessful()) {
            return [
                'success' => false,
                'output' => null,
                'error' => trim($process->getErrorOutput() ?: $process->getOutput()),
                'context' => $existingContext,
            ];
        }

        return [
            'success' => true,
            'output' => trim($process->getOutput()),
            'error' => null,
            'context' => $this->appendContext($existingContext, $command),
        ];
    }

    private function buildScript(string $command, ?string $existingContext = null): string
    {
        $parts = [];

        if (! empty($existingContext)) {
            $parts[] = $existingContext;
        }

        $parts[] = $command;

        return implode('; ', $parts);
    }

    private function appendContext(?string $existingContext, string $command): string
    {
        return trim(($existingContext ? $existingContext . '; ' : '') . $command);
    }
}