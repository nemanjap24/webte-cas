<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DocsController extends Controller
{
    public function openapi(): JsonResponse
    {
        return response()->json($this->spec());
    }

    public function pdf(): Response
    {
        $spec = $this->spec();
        $title = $spec['info']['title'] ?? 'API Documentation';

        $pdf = Pdf::loadView('docs.api-pdf', [
            'title' => $title,
            'spec' => $spec,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('webte2-cas-api-documentation.pdf');
    }

    private function spec(): array
    {
        return [
            'openapi' => '3.0.3',
            'info' => [
                'title' => 'WEBTE2 CAS API',
                'version' => '1.0.0',
                'description' => 'REST API for CAS execution, simulations, logs, CSV export and animation statistics.',
            ],
            'servers' => [
                [
                    'url' => url('/api'),
                    'description' => 'Current application API',
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'ApiKeyAuth' => [
                        'type' => 'apiKey',
                        'in' => 'header',
                        'name' => 'X-API-KEY',
                    ],
                ],
                'schemas' => [
                    'CasExecuteRequest' => [
                        'type' => 'object',
                        'properties' => [
                            'command' => [
                                'type' => 'string',
                                'example' => 'a=1',
                                'description' => 'CAS command to execute.',
                            ],
                            'session_token' => [
                                'type' => 'string',
                                'example' => 'test-user-1',
                                'description' => 'Session token used for preserving CAS variables between requests.',
                            ],
                        ],
                        'required' => ['command', 'session_token'],
                    ],

                    'CasExecuteResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'success' => [
                                'type' => 'boolean',
                                'example' => true,
                            ],
                            'output' => [
                                'type' => 'string',
                                'example' => 'a = 1',
                            ],
                            'error' => [
                                'type' => 'string',
                                'nullable' => true,
                                'example' => null,
                            ],
                            'session_token' => [
                                'type' => 'string',
                                'example' => 'test-user-1',
                            ],
                        ],
                    ],

                    'InvertedPendulumRequest' => [
                        'type' => 'object',
                        'properties' => [
                            'target_position' => [
                                'type' => 'number',
                                'format' => 'float',
                                'example' => 0.2,
                                'description' => 'Target cart position.',
                            ],
                            'session_token' => [
                                'type' => 'string',
                                'example' => 'test-user-1',
                                'description' => 'Session token used for preserving simulation/CAS context.',
                            ],
                        ],
                        'required' => ['target_position'],
                    ],

                    'BallBeamRequest' => [
                        'type' => 'object',
                        'properties' => [
                            'target_position' => [
                                'type' => 'number',
                                'format' => 'float',
                                'example' => 0.25,
                                'description' => 'Target ball position on the beam.',
                            ],
                            'session_token' => [
                                'type' => 'string',
                                'example' => 'test-user-1',
                                'description' => 'Session token used for preserving simulation/CAS context.',
                            ],
                        ],
                        'required' => ['target_position'],
                    ],

                    'SimulationResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'success' => [
                                'type' => 'boolean',
                                'example' => true,
                            ],
                            'data' => [
                                'type' => 'object',
                                'properties' => [
                                    'time' => [
                                        'type' => 'array',
                                        'items' => ['type' => 'number'],
                                        'example' => [0, 0.05, 0.10, 0.15],
                                        'description' => 'Simulation time values.',
                                    ],
                                    'output' => [
                                        'type' => 'array',
                                        'items' => ['type' => 'number'],
                                        'example' => [0, 0.08, 0.16, 0.20],
                                        'description' => 'Primary output values for graph rendering.',
                                    ],
                                ],
                            ],
                            'error' => [
                                'type' => 'string',
                                'nullable' => true,
                                'example' => null,
                            ],
                        ],
                    ],

                    'LogItem' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer', 'example' => 1],
                            'session_token' => ['type' => 'string', 'nullable' => true, 'example' => 'test-user-1'],
                            'command' => ['type' => 'string', 'example' => 'a=1'],
                            'output' => ['type' => 'string', 'nullable' => true, 'example' => 'a = 1'],
                            'is_success' => ['type' => 'boolean', 'example' => true],
                            'error_message' => ['type' => 'string', 'nullable' => true, 'example' => null],
                            'executed_at' => ['type' => 'string', 'format' => 'date-time', 'example' => '2026-05-21T16:00:00Z'],
                        ],
                    ],

                    'LogsResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'data' => [
                                'type' => 'array',
                                'items' => [
                                    '$ref' => '#/components/schemas/LogItem',
                                ],
                            ],
                        ],
                    ],

                    'StatisticsSummaryItem' => [
                        'type' => 'object',
                        'properties' => [
                            'animation_type' => ['type' => 'string', 'example' => 'inverted-pendulum'],
                            'count' => ['type' => 'integer', 'example' => 5],
                        ],
                    ],

                    'StatisticsDetailItem' => [
                        'type' => 'object',
                        'properties' => [
                            'animation_type' => ['type' => 'string', 'example' => 'ball-beam'],
                            'user_token' => ['type' => 'string', 'example' => 'anon-user-123'],
                            'city' => ['type' => 'string', 'nullable' => true, 'example' => null],
                            'country' => ['type' => 'string', 'nullable' => true, 'example' => null],
                            'used_at' => ['type' => 'string', 'format' => 'date-time', 'example' => '2026-05-21T16:05:00Z'],
                        ],
                    ],

                    'StatisticsResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'summary' => [
                                'type' => 'array',
                                'items' => [
                                    '$ref' => '#/components/schemas/StatisticsSummaryItem',
                                ],
                            ],
                            'details' => [
                                'type' => 'array',
                                'items' => [
                                    '$ref' => '#/components/schemas/StatisticsDetailItem',
                                ],
                            ],
                            'interval_minutes' => [
                                'type' => 'integer',
                                'example' => 10,
                            ],
                        ],
                    ],

                    'ErrorResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'message' => [
                                'type' => 'string',
                                'example' => 'Validation or execution error.',
                            ],
                        ],
                    ],
                ],
            ],
            'security' => [
                ['ApiKeyAuth' => []],
            ],
            'paths' => [
                '/cas/execute' => [
                    'post' => [
                        'summary' => 'Execute a CAS command',
                        'tags' => ['CAS'],
                        'requestBody' => [
                            'required' => true,
                            'description' => 'CAS command payload.',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/CasExecuteRequest',
                                    ],
                                    'example' => [
                                        'command' => 'a=1',
                                        'session_token' => 'test-user-1',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'CAS command executed successfully',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/CasExecuteResponse',
                                        ],
                                    ],
                                ],
                            ],
                            '422' => [
                                'description' => 'Validation or execution error',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/ErrorResponse',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],

                '/logs' => [
                    'get' => [
                        'summary' => 'Retrieve CAS request logs',
                        'tags' => ['Logs'],
                        'parameters' => [
                            [
                                'name' => 'session_token',
                                'in' => 'query',
                                'required' => false,
                                'description' => 'Optional session token filter.',
                                'schema' => ['type' => 'string'],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Paginated log list',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/LogsResponse',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],

                '/logs/export' => [
                    'get' => [
                        'summary' => 'Export CAS logs to CSV',
                        'tags' => ['Logs'],
                        'parameters' => [
                            [
                                'name' => 'session_token',
                                'in' => 'query',
                                'required' => false,
                                'description' => 'Optional session token filter.',
                                'schema' => ['type' => 'string'],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'CSV file download',
                                'content' => [
                                    'text/csv' => [
                                        'schema' => [
                                            'type' => 'string',
                                            'format' => 'binary',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],

                '/simulations/inverted-pendulum' => [
                    'post' => [
                        'summary' => 'Run inverted pendulum simulation',
                        'tags' => ['Simulations'],
                        'requestBody' => [
                            'required' => true,
                            'description' => 'Parameters for inverted pendulum simulation.',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/InvertedPendulumRequest',
                                    ],
                                    'example' => [
                                        'target_position' => 0.2,
                                        'session_token' => 'test-user-1',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Simulation output',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/SimulationResponse',
                                        ],
                                    ],
                                ],
                            ],
                            '422' => [
                                'description' => 'Validation or simulation error',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/ErrorResponse',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],

                '/simulations/ball-beam' => [
                    'post' => [
                        'summary' => 'Run ball and beam simulation',
                        'tags' => ['Simulations'],
                        'requestBody' => [
                            'required' => true,
                            'description' => 'Parameters for ball and beam simulation.',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/BallBeamRequest',
                                    ],
                                    'example' => [
                                        'target_position' => 0.25,
                                        'session_token' => 'test-user-1',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Simulation output',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/SimulationResponse',
                                        ],
                                    ],
                                ],
                            ],
                            '422' => [
                                'description' => 'Validation or simulation error',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/ErrorResponse',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],

                '/statistics' => [
                    'get' => [
                        'summary' => 'Retrieve animation usage statistics',
                        'tags' => ['Statistics'],
                        'responses' => [
                            '200' => [
                                'description' => 'Statistics response',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/StatisticsResponse',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}