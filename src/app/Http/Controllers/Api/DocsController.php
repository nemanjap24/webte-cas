<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DocsController extends Controller
{
    public function openapi(): JsonResponse
    {
        $spec = [
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
                            'command' => ['type' => 'string', 'example' => 'a=1'],
                            'session_token' => ['type' => 'string', 'example' => 'test-user-1'],
                        ],
                        'required' => ['command', 'session_token'],
                    ],
                    'CasExecuteResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'success' => ['type' => 'boolean', 'example' => true],
                            'output' => ['type' => 'string', 'example' => 'a = 1'],
                            'error' => ['type' => 'string', 'nullable' => true, 'example' => null],
                            'session_token' => ['type' => 'string', 'example' => 'test-user-1'],
                        ],
                    ],
                    'SimulationRequest' => [
                        'type' => 'object',
                        'properties' => [
                            'target_position' => ['type' => 'number', 'format' => 'float', 'example' => 0.2],
                            'session_token' => ['type' => 'string', 'example' => 'test-user-1'],
                        ],
                    ],
                    'StatisticsResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'summary' => ['type' => 'array', 'items' => ['type' => 'object']],
                            'details' => ['type' => 'array', 'items' => ['type' => 'object']],
                            'interval_minutes' => ['type' => 'integer', 'example' => 10],
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
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/CasExecuteRequest'],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'CAS command executed successfully',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/CasExecuteResponse'],
                                    ],
                                ],
                            ],
                            '422' => [
                                'description' => 'Validation or execution error',
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
                                'schema' => ['type' => 'string'],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Paginated log list',
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
                                'schema' => ['type' => 'string'],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'CSV file download',
                            ],
                        ],
                    ],
                ],
                '/simulations/inverted-pendulum' => [
                    'post' => [
                        'summary' => 'Run inverted pendulum simulation',
                        'tags' => ['Simulations'],
                        'requestBody' => [
                            'required' => false,
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/SimulationRequest'],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Simulation output',
                            ],
                        ],
                    ],
                ],
                '/simulations/ball-beam' => [
                    'post' => [
                        'summary' => 'Run ball and beam simulation',
                        'tags' => ['Simulations'],
                        'requestBody' => [
                            'required' => false,
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/SimulationRequest'],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Simulation output',
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
                                        'schema' => ['$ref' => '#/components/schemas/StatisticsResponse'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return response()->json($spec);
    }
}