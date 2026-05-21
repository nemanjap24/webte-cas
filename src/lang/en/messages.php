<?php

return [
    'badge' => 'Semester project • CAS + REST API',
    'title' => 'Control of dynamic systems in a web app',
    'subtitle' => 'A single app for running calculations, plotting charts, and animating two systems: inverted pendulum and ball-and-beam.',
    'ctaPrimary' => 'Open calculation module',
    'ctaSecondary' => 'Reference model (CTMS)',
    'sectionsTitle' => 'Key assignment blocks',
    'cards' => [
        [
            'title' => 'CAS API layer',
            'text' => 'The backend will connect Octave (or another CAS) through a REST API. Simulation outputs are computed server-side.',
        ],
        [
            'title' => '2 simulations',
            'text' => 'Implement two models: inverted pendulum and ball-and-beam. Plot and animation should stay synchronized over time.',
        ],
        [
            'title' => 'Security + logging',
            'text' => 'CAS calls must be protected with token/API key auth. Every request is logged and exportable to CSV.',
        ],
        [
            'title' => 'Docs + Docker',
            'text' => 'OpenAPI documentation (plus PDF export) and full app containerization using Docker.',
        ],
    ],
    'footer' => 'First screen is ready. Next we can build the calculation form or the first animation.',

    // Console page translations
    'console_title' => 'Calculation module (backend-connected)',
    'console_subtitle' => 'Requests now go through a backend mutation. Outputs are server-logged and variables remain persisted per session token.',
    'sessionToken' => 'Session token',
    'apiKey' => 'API key',
    'run' => 'Run commands',
    'clearScript' => 'Clear script',
    'loadBall' => 'Sample: ball-and-beam',
    'loadPendulum' => 'Sample: inverted pendulum',
    'lastBatch' => 'Latest batch output',
    'outputHistory' => 'Output history (logs)',
    'vars' => 'Saved session variables',
    'back' => 'Back to home',
];
