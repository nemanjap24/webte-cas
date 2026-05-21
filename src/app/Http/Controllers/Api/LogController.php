<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CasLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logs = CasLog::query()
            ->when($request->filled('session_token'), function ($query) use ($request) {
                $query->where('session_token', $request->string('session_token'));
            })
            ->orderByDesc('executed_at')
            ->paginate(20);

        return response()->json($logs);
    }

    public function export(Request $request): StreamedResponse
    {
        $fileName = 'cas_logs_' . now()->format('Y_m_d_H_i_s') . '.csv';

        $logs = CasLog::query()
            ->when($request->filled('session_token'), function ($query) use ($request) {
                $query->where('session_token', $request->string('session_token'));
            })
            ->orderByDesc('executed_at')
            ->get();

        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'id',
                'session_token',
                'command',
                'output',
                'is_success',
                'error_message',
                'executed_at',
                'created_at',
            ]);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->session_token,
                    $log->command,
                    $log->output,
                    $log->is_success ? 1 : 0,
                    $log->error_message,
                    optional($log->executed_at)->toDateTimeString(),
                    optional($log->created_at)->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}