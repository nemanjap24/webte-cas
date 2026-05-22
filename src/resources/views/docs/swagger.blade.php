@extends('layouts.app')

@section('title', 'API Documentation')

@section('content')
<div class="bg-white min-h-screen">
    <div id="swagger-ui"></div>
</div>

<link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css" />
<script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js"></script>
<script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-standalone-preset.js"></script>

<script>
window.onload = () => {
    window.ui = SwaggerUIBundle({
        url: "/api/docs/openapi",
        dom_id: '#swagger-ui',
        deepLinking: true,
        presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIStandalonePreset
        ],
        layout: "BaseLayout",
    });
};
</script>

<style>
    /* Fix for dark theme layout if needed, though Swagger UI is white-based */
    .swagger-ui .topbar { display: none; }
</style>
@endsection
