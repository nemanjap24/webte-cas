<?php

return [
    'badge' => 'Final Project 2026',
    'title' => 'Web-based CAS Integration',
    'subtitle' => 'A high-performance REST API platform for Computer Aided Systems. Execute Octave commands, visualize dynamic simulations, and monitor usage statistics in real-time.',
    'ctaPrimary' => 'Launch Console',
    'ctaSecondary' => 'API Documentation',
    'sectionsTitle' => 'Core Capabilities',
    'cards' => [
        'simulations' => [
            'title' => 'Dynamic Simulations',
            'text' => 'Interactive visualizations of Inverted Pendulum and Ball & Beam systems with synchronized real-time graphing.',
        ],
        'api' => [
            'title' => 'Secure CAS API',
            'text' => 'Robust REST endpoints protected by API keys, capable of executing complex Octave scripts with session persistence.',
        ],
        'docs' => [
            'title' => 'Automated Docs',
            'text' => 'Dynamically generated PDF documentation and interactive Swagger UI to explore our system capabilities.',
        ],
    ],
    'download_pdf' => 'Download PDF',
    'footer_text' => 'WEBTE CAS Team',

    // Console page
    'console_title' => 'Calculation Console',
    'console_subtitle' => 'Execute raw Octave commands. Variables are persisted across your session.',
    'run' => 'Run commands',
    'running' => 'Running...',
    'clearScript' => 'Clear script',
    'loadBall' => 'Ball & Beam sample',
    'loadPendulum' => 'Pendulum sample',
    'lastBatch' => 'Latest Execution',
    'noBatch' => 'No batch executed yet. Enter commands and press Run.',
    'back' => 'Back to home',
    'console_errors' => [
        'server' => 'The server could not process the command.',
        'validation' => 'Enter a valid Octave command before running it.',
        'undefined_variable' => 'Variable ":name" is not defined in this session.',
        'parse' => 'The command contains a syntax error. Check the expression and try again.',
        'generic' => 'The command could not be executed.',
    ],

    // Navigation
    'nav' => [
        'home' => 'Home',
        'console' => 'Console',
        'animations' => 'Animations',
        'logs' => 'Logs',
        'stats' => 'Statistics',
    ],

    // Animations page
    'animations_title' => 'System Simulations',
    'animations_subtitle' => 'Interactive animations of dynamic systems with synchronized live charts.',
    'select_system' => 'Select System',
    'target_position' => 'Target Position',
    'run_simulation' => 'Run Simulation',
    'reset_state' => 'Reset State',
    'computing' => 'Computing results on server...',

    // Logs page
    'logs_title' => 'Request History',
    'logs_subtitle' => 'All CAS interactions are logged here and available for CSV export.',
    'export_csv' => 'Export to CSV',
    'status_success' => 'Success',
    'status_error' => 'Error',
    'no_logs' => 'No interactions recorded yet. Go to the Console or Animations to generate logs.',

    // Stats page
    'stats_title' => 'Usage Statistics',
    'stats_subtitle' => 'Overview of simulation usage across all users.',
    'unique_sessions' => 'Validated unique sessions',
    'recent_activity' => 'Recent Activity Details',
    'time' => 'Time',
    'animation' => 'Animation',
    'location' => 'Location',
    'no_stats' => 'No activity recorded yet.',
    'location_unavailable' => 'Location data unavailable',
];
