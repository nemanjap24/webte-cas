<?php

namespace App\Services\Simulation;

use Symfony\Component\Process\Process;

class SimulationService
{
    public function runInvertedPendulum(float $targetPosition = 0.2, ?array $initialState = null): array
    {
        $init = $initialState ? '[' . implode('; ', $initialState) . ']' : '[0; 0; 0; 0]';
        
        $script = <<<OCTAVE
pkg load control;
M = 0.5;
m = 0.2;
b = 0.1;
I = 0.006;
g = 9.8;
l = 0.3;
p = I*(M+m)+M*m*l^2;
A = [0 1 0 0; 0 -(I+m*l^2)*b/p (m^2*g*l^2)/p 0; 0 0 0 1; 0 -(m*l*b)/p m*g*l*(M+m)/p 0];
B = [0; (I+m*l^2)/p; 0; m*l/p];
C = [1 0 0 0; 0 0 1 0];
D = [0; 0];
K = lqr(A,B,C'*C,1);
Ac = A-B*K;
N = -inv(C(1,:)*inv(A-B*K)*B);
sys = ss(Ac,B*N,C,D);
t = 0:0.05:10;
r = {$targetPosition};
[y,t,x] = lsim(sys,r*ones(size(t)),t,{$init});
final_state = x(size(x,1),:);
disp(jsonencode(struct("time", t, "cart_position", y(:,1), "pendulum_angle", y(:,2), "final_state", final_state)));
OCTAVE;

        return $this->runOctaveJson($script);
    }

    public function runBallBeam(float $targetPosition = 0.25, ?array $initialState = null): array
    {
        $init = $initialState ? '[' . implode('; ', $initialState) . ']' : '[0; 0; 0; 0]';

        $script = <<<OCTAVE
pkg load control;
m = 0.111;
R = 0.015;
g = -9.8;
J = 9.99e-6;
H = -m*g/(J/(R^2)+m);
A = [0 1 0 0; 0 0 H 0; 0 0 0 1; 0 0 0 0];
B = [0; 0; 0; 1];
C = [1 0 0 0];
D = [0];
K = place(A,B,[-2+2i,-2-2i,-20,-80]);
N = -inv(C*inv(A-B*K)*B);
sys = ss(A-B*K,B*N,C,D);
t = 0:0.01:5;
r = {$targetPosition};
[y,t,x] = lsim(sys,r*ones(size(t)),t,{$init});
final_state = x(size(x,1),:);
disp(jsonencode(struct("time", t, "ball_position", y, "beam_angle", x(:,3), "final_state", final_state)));
OCTAVE;

        return $this->runOctaveJson($script);
    }

    private function runOctaveJson(string $script): array
    {
        $process = new Process([
            config('cas.executable_path'),
            '--quiet',
            '--eval',
            $script,
        ]);

        $process->setTimeout(30);
        $process->run();

        usleep(config('cas.slowdown_ms') * 1000);

        if (! $process->isSuccessful()) {
            return [
                'success' => false,
                'data' => null,
                'error' => trim($process->getErrorOutput() ?: $process->getOutput()),
            ];
        }

        $output = trim($process->getOutput());
        $decoded = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'data' => null,
                'error' => 'Invalid JSON returned from Octave: ' . $output,
            ];
        }

        return [
            'success' => true,
            'data' => $decoded,
            'error' => null,
        ];
    }
}