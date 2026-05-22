<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.45;
            color: #111;
            margin: 28px 24px 40px 24px;
        }

        h1, h2, h3, h4 {
            margin: 0 0 8px 0;
            padding: 0;
        }

        h1 {
            font-size: 22px;
            border-bottom: 1px solid #cfcfcf;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }

        h2 {
            font-size: 16px;
            margin-top: 24px;
            border-bottom: 1px solid #e2e2e2;
            padding-bottom: 4px;
        }

        h3 {
            font-size: 13px;
            margin-top: 16px;
        }

        h4 {
            font-size: 12px;
            margin-top: 12px;
        }

        p {
            margin: 0 0 8px 0;
        }

        ul {
            margin: 6px 0 10px 18px;
            padding: 0;
        }

        li {
            margin: 0 0 4px 0;
        }

        code {
            font-family: DejaVu Sans Mono, monospace;
            background: #f3f3f3;
            padding: 1px 3px;
        }

        pre {
            white-space: pre-wrap;
            word-break: break-word;
            background: #f7f7f7;
            border: 1px solid #d8d8d8;
            padding: 8px;
            margin: 6px 0 12px 0;
            font-size: 10px;
            line-height: 1.4;
            font-family: DejaVu Sans Mono, monospace;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 14px 0;
            table-layout: fixed;
        }

        table, th, td {
            border: 1px solid #cfcfcf;
        }

        th, td {
            padding: 6px;
            vertical-align: top;
            text-align: left;
            font-size: 10px;
            word-wrap: break-word;
        }

        th {
            background: #f2f2f2;
        }

        .meta {
            margin-bottom: 16px;
            color: #444;
        }

        .section {
            margin-bottom: 18px;
        }

        .endpoint {
            margin-bottom: 22px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #d9d9d9;
            page-break-inside: avoid;
        }

        .method {
            display: inline-block;
            font-size: 10px;
            font-weight: bold;
            color: #fff;
            background: #444;
            padding: 3px 7px;
            margin-bottom: 8px;
        }

        .tag {
            display: inline-block;
            font-size: 10px;
            background: #ececec;
            padding: 2px 6px;
            margin-right: 4px;
            margin-bottom: 4px;
        }

        .muted {
            color: #666;
        }

        .schema-box {
            border: 1px solid #d9d9d9;
            background: #fafafa;
            padding: 8px;
            margin: 8px 0 12px 0;
        }

        .small {
            font-size: 10px;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>

    <div class="meta">
        <p><strong>Version:</strong> {{ $spec['info']['version'] ?? '1.0.0' }}</p>
        <p><strong>Description:</strong> {{ $spec['info']['description'] ?? '' }}</p>
        <p><strong>Generated at:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="section">
        <h2>Servers</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 55%;">URL</th>
                    <th style="width: 45%;">Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach(($spec['servers'] ?? []) as $server)
                    <tr>
                        <td>{{ $server['url'] ?? '' }}</td>
                        <td>{{ $server['description'] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Security</h2>
        <p>This API is protected by <code>X-API-KEY</code> header authentication.</p>
    </div>

    <div class="section">
        <h2>Endpoints</h2>

        @foreach(($spec['paths'] ?? []) as $path => $methods)
            @foreach($methods as $method => $operation)
                <div class="endpoint">
                    <div class="method">{{ strtoupper($method) }}</div>
                    <h3>{{ $path }}</h3>

                    @if(!empty($operation['summary']))
                        <p><strong>Summary:</strong> {{ $operation['summary'] }}</p>
                    @endif

                    @if(!empty($operation['tags']))
                        <p>
                            <strong>Tags:</strong>
                            @foreach($operation['tags'] as $tag)
                                <span class="tag">{{ $tag }}</span>
                            @endforeach
                        </p>
                    @endif

                    @if(!empty($operation['parameters']))
                        <h4>Parameters</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 18%;">Name</th>
                                    <th style="width: 10%;">In</th>
                                    <th style="width: 10%;">Required</th>
                                    <th style="width: 12%;">Type</th>
                                    <th style="width: 50%;">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($operation['parameters'] as $parameter)
                                    <tr>
                                        <td>{{ $parameter['name'] ?? '' }}</td>
                                        <td>{{ $parameter['in'] ?? '' }}</td>
                                        <td>{{ !empty($parameter['required']) ? 'Yes' : 'No' }}</td>
                                        <td>{{ $parameter['schema']['type'] ?? '' }}</td>
                                        <td>{{ $parameter['description'] ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    @if(!empty($operation['requestBody']))
                        <h4>Request body</h4>

                        @if(!empty($operation['requestBody']['description']))
                            <p>{{ $operation['requestBody']['description'] }}</p>
                        @endif

                        @php
                            $jsonContent = $operation['requestBody']['content']['application/json'] ?? null;
                            $requestSchemaRef = $jsonContent['schema']['$ref'] ?? null;
                            $requestExample = $jsonContent['example'] ?? null;
                        @endphp

                        @if($requestSchemaRef)
                            <p><strong>Schema reference:</strong> <code>{{ $requestSchemaRef }}</code></p>
                        @endif

                        @if($requestExample)
                            <p><strong>Example JSON:</strong></p>
                            <pre>{{ json_encode($requestExample, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        @endif
                    @endif

                    @if(!empty($operation['responses']))
                        <h4>Responses</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 12%;">Status</th>
                                    <th style="width: 28%;">Description</th>
                                    <th style="width: 60%;">Schema</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($operation['responses'] as $status => $response)
                                    @php
                                        $schema = $response['content']['application/json']['schema']
                                            ?? $response['content']['text/csv']['schema']
                                            ?? $response['content']['application/pdf']['schema']
                                            ?? null;
                                        $responseContent = '';

                                        if (!empty($schema['$ref'])) {
                                            $responseContent = $schema['$ref'];
                                        } elseif (!empty($schema['oneOf'])) {
                                            $responseContent = collect($schema['oneOf'])
                                                ->map(fn ($item) => $item['$ref'] ?? ($item['type'] ?? 'schema'))
                                                ->implode(', ');
                                        } elseif (!empty($schema['format'])) {
                                            $responseContent = $schema['format'];
                                        } elseif (!empty($schema['type'])) {
                                            $responseContent = $schema['type'];
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $status }}</td>
                                        <td>{{ $response['description'] ?? '' }}</td>
                                        <td>{{ $responseContent }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endforeach
        @endforeach
    </div>

    <div class="section">
        <h2>Schemas</h2>

        @foreach(($spec['components']['schemas'] ?? []) as $schemaName => $schema)
            <div class="schema-box">
                <h3>{{ $schemaName }}</h3>

                @if(!empty($schema['description']))
                    <p>{{ $schema['description'] }}</p>
                @endif

                <p class="small">
                    <strong>Type:</strong> {{ $schema['type'] ?? 'object' }}
                    @if(!empty($schema['required']))
                        | <strong>Required:</strong> {{ implode(', ', $schema['required']) }}
                    @endif
                </p>

                @if(!empty($schema['properties']))
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 18%;">Property</th>
                                <th style="width: 12%;">Type</th>
                                <th style="width: 18%;">Format / Ref</th>
                                <th style="width: 20%;">Example</th>
                                <th style="width: 32%;">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schema['properties'] as $propertyName => $property)
                                <tr>
                                    <td>{{ $propertyName }}</td>
                                    <td>{{ $property['type'] ?? 'object' }}</td>
                                    <td>
                                        @if(!empty($property['format']))
                                            {{ $property['format'] }}
                                        @elseif(!empty($property['items']['$ref']))
                                            {{ $property['items']['$ref'] }}
                                        @elseif(!empty($property['items']['type']))
                                            array&lt;{{ $property['items']['type'] }}&gt;
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if(array_key_exists('example', $property ?? []))
                                            {{ is_array($property['example']) ? json_encode($property['example']) : var_export($property['example'], true) }}
                                        @elseif(!empty($property['items']['example']))
                                            {{ is_array($property['items']['example']) ? json_encode($property['items']['example']) : var_export($property['items']['example'], true) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $property['description'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="muted">No direct properties listed.</p>
                @endif
            </div>
        @endforeach
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont("Helvetica", "normal");
            $pdf->page_text(34, 18, "{{ $title }}", $font, 10, [0.4, 0.4, 0.4]);
            $pdf->page_text(520, 810, "{PAGE_NUM}/{PAGE_COUNT}", $font, 10, [0.4, 0.4, 0.4]);
        }
    </script>
</body>
</html>
