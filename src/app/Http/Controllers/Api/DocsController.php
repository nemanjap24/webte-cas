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
                                        'count' => ['type' => 'integer'],
                                    ],
                                ],
                            ],
                            'details' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'animation_type' => ['type' => 'string'],
                                        'city' => ['type' => 'string', 'nullable' => true],
                                        'country' => ['type' => 'string', 'nullable' => true],
                                        'used_at' => ['type' => 'string', 'format' => 'date-time'],
                                    ],
                                ],
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
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/simulations/inverted-pendulum' => [
                    'post' => [
                        'summary' => 'Run inverted pendulum simulation',
                        'tags' => ['Simulations'],
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/simulations/ball-beam' => [
                    'post' => [
                        'summary' => 'Run ball and beam simulation',
                        'tags' => ['Simulations'],
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/logs' => [
                    'get' => [
                        'summary' => 'Retrieve CAS logs',
                        'tags' => ['Logs'],
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/logs/export' => [
                    'get' => [
                        'summary' => 'Export logs to CSV',
                        'tags' => ['Logs'],
                        'responses' => [
                            '200' => ['description' => 'CSV File'],
                        ],
                    ],
                ],
                '/statistics' => [
                    'get' => [
                        'summary' => 'Retrieve usage statistics',
                        'tags' => ['Statistics'],
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/docs/openapi' => [
                    'get' => [
                        'summary' => 'Retrieve OpenAPI spec (JSON)',
                        'tags' => ['Documentation'],
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/docs/pdf' => [
                    'get' => [
                        'summary' => 'Download API documentation (PDF)',
                        'tags' => ['Documentation'],
                        'responses' => [
                            '200' => ['description' => 'PDF File'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
