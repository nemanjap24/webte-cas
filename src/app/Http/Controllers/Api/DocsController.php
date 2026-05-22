<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DocsController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        return view('docs.swagger');
    }

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
        ])
        ->setPaper('a4', 'portrait')
        ->setOption('isPhpEnabled', true);

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

                    'ErrorResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'message' => [
                                'type' => 'string',
                                'example' => 'Unauthorized',
                            ],
                        ],
                    ],

                    'ValidationErrorResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'message' => [
                                'type' => 'string',
                                'example' => 'The command field is required.',
                            ],
                            'errors' => [
                                'type' => 'object',
                                'additionalProperties' => [
                                    'type' => 'array',
                                    'items' => ['type' => 'string'],
                                ],
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
                            'reset' => [
                                'type' => 'boolean',
                                'example' => false,
                                'description' => 'Reset simulation state to zero.',
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
                            'reset' => [
                                'type' => 'boolean',
                                'example' => false,
                                'description' => 'Reset simulation state to zero.',
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
                                    ],
                                    'output' => [
                                        'type' => 'array',
                                        'items' => ['type' => 'number'],
                                        'description' => 'Generic simulation output values, when present.',
                                    ],
                                    'cart_position' => [
                                        'type' => 'array',
                                        'items' => ['type' => 'number'],
                                        'description' => 'Inverted pendulum cart position values in metres.',
                                    ],
                                    'pendulum_angle' => [
                                        'type' => 'array',
                                        'items' => ['type' => 'number'],
                                        'description' => 'Inverted pendulum angle values in radians.',
                                    ],
                                    'ball_position' => [
                                        'type' => 'array',
                                        'items' => ['type' => 'number'],
                                        'description' => 'Ball position values in metres.',
                                    ],
                                    'beam_angle' => [
                                        'type' => 'array',
                                        'items' => ['type' => 'number'],
                                        'description' => 'Beam angle values in radians.',
                                    ],
                                    'final_state' => [
                                        'type' => 'array',
                                        'items' => ['type' => 'number'],
                                        'description' => 'State vector at the end of simulation.',
                                    ],
                                ],
                            ],
                            'error' => [
                                'type' => 'string',
                                'nullable' => true,
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

                    'StatisticsResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'summary' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'animation_type' => ['type' => 'string'],
                                        'usage_count' => ['type' => 'integer'],
                                    ],
                                ],
                            ],
                            'details' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'user_token' => ['type' => 'string'],
                                        'animation_type' => ['type' => 'string'],
                                        'city' => ['type' => 'string', 'nullable' => true],
                                        'country' => ['type' => 'string', 'nullable' => true],
                                        'used_at' => ['type' => 'string', 'format' => 'date-time'],
                                    ],
                                ],
                            ],
                            'interval_minutes' => ['type' => 'integer'],
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
                                    'example' => [
                                        'command' => 'a=1+1',
                                        'session_token' => 'test-user-1',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Command executed successfully.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/CasExecuteResponse'],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Missing or invalid API key.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/ErrorResponse'],
                                    ],
                                ],
                            ],
                            '422' => [
                                'description' => 'Validation or CAS execution error.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'oneOf' => [
                                                ['$ref' => '#/components/schemas/CasExecuteResponse'],
                                                ['$ref' => '#/components/schemas/ValidationErrorResponse'],
                                            ],
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
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/InvertedPendulumRequest'],
                                    'example' => [
                                        'target_position' => 0.2,
                                        'session_token' => 'test-user-1',
                                        'reset' => false,
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Simulation completed successfully.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/SimulationResponse'],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Missing or invalid API key.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/ErrorResponse'],
                                    ],
                                ],
                            ],
                            '422' => [
                                'description' => 'Validation or simulation error.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'oneOf' => [
                                                ['$ref' => '#/components/schemas/SimulationResponse'],
                                                ['$ref' => '#/components/schemas/ValidationErrorResponse'],
                                            ],
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
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/BallBeamRequest'],
                                    'example' => [
                                        'target_position' => 0.25,
                                        'session_token' => 'test-user-1',
                                        'reset' => false,
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Simulation completed successfully.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/SimulationResponse'],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Missing or invalid API key.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/ErrorResponse'],
                                    ],
                                ],
                            ],
                            '422' => [
                                'description' => 'Validation or simulation error.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'oneOf' => [
                                                ['$ref' => '#/components/schemas/SimulationResponse'],
                                                ['$ref' => '#/components/schemas/ValidationErrorResponse'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/logs' => [
                    'get' => [
                        'summary' => 'Retrieve CAS logs',
                        'tags' => ['Logs'],
                        'parameters' => [
                            [
                                'name' => 'session_token',
                                'in' => 'query',
                                'required' => false,
                                'schema' => ['type' => 'string'],
                                'description' => 'Optional session token filter.',
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Paginated CAS request logs.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/LogsResponse'],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Missing or invalid API key.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/ErrorResponse'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/logs/export' => [
                    'get' => [
                        'summary' => 'Export logs to CSV',
                        'tags' => ['Logs'],
                        'parameters' => [
                            [
                                'name' => 'session_token',
                                'in' => 'query',
                                'required' => false,
                                'schema' => ['type' => 'string'],
                                'description' => 'Optional session token filter.',
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'CSV file containing CAS logs.',
                                'content' => [
                                    'text/csv' => [
                                        'schema' => [
                                            'type' => 'string',
                                            'format' => 'binary',
                                        ],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Missing or invalid API key.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/ErrorResponse'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/statistics' => [
                    'get' => [
                        'summary' => 'Retrieve usage statistics',
                        'tags' => ['Statistics'],
                        'responses' => [
                            '200' => [
                                'description' => 'Animation usage statistics.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/StatisticsResponse'],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Missing or invalid API key.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/ErrorResponse'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/docs/openapi' => [
                    'get' => [
                        'summary' => 'Retrieve OpenAPI spec (JSON)',
                        'tags' => ['Documentation'],
                        'security' => [],
                        'responses' => [
                            '200' => [
                                'description' => 'OpenAPI JSON document.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/docs/pdf' => [
                    'get' => [
                        'summary' => 'Download API documentation (PDF)',
                        'tags' => ['Documentation'],
                        'security' => [],
                        'responses' => [
                            '200' => [
                                'description' => 'Dynamically generated API documentation PDF.',
                                'content' => [
                                    'application/pdf' => [
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
            ],
        ];
    }
}
