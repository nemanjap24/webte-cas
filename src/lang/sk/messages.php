<?php

return [
    'badge' => 'Záverečný projekt 2026',
    'title' => 'Webová integrácia CAS',
    'subtitle' => 'Vysokovýkonná platforma REST API pre systémy Computer Aided Systems. Spúšťajte príkazy Octave, vizualizujte dynamické simulácie a monitorujte štatistiky používania v reálnom čase.',
    'ctaPrimary' => 'Spustiť konzolu',
    'ctaSecondary' => 'API dokumentácia',
    'sectionsTitle' => 'Základné možnosti',
    'cards' => [
        'simulations' => [
            'title' => 'Dynamické simulácie',
            'text' => 'Interaktívne vizualizácie systémov inverzného kyvadla a guličky na tyči so synchronizovaným grafom v reálnom čase.',
        ],
        'api' => [
            'title' => 'Zabezpečené CAS API',
            'text' => 'Robustné REST endpointy chránené API kľúčmi, schopné vykonávať zložité Octave skripty s perzistenciou relácie.',
        ],
        'docs' => [
            'title' => 'Automatizovaná dokumentácia',
            'text' => 'Dynamicky generovaná PDF dokumentácia a interaktívne Swagger UI na preskúmanie možností nášho systému.',
        ],
    ],
    'download_pdf' => 'Stiahnuť PDF',
    'footer_text' => 'WEBTE CAS Tím',

    // Stránka konzoly
    'console_title' => 'Výpočtová konzola',
    'console_subtitle' => 'Vykonávajte čisté príkazy Octave. Premenné sú zachované počas celej vašej relácie.',
    'run' => 'Spustiť príkazy',
    'running' => 'Spúšťam...',
    'clearScript' => 'Vymazať skript',
    'loadBall' => 'Ukážka: Gulička na tyči',
    'loadPendulum' => 'Ukážka: Kyvadlo',
    'lastBatch' => 'Posledné vykonanie',
    'noBatch' => 'Zatiaľ nebol vykonaný žiadny príkaz. Zadajte príkazy a stlačte Spustiť.',
    'back' => 'Späť na domov',

    // Navigácia
    'nav' => [
        'home' => 'Domov',
        'console' => 'Konzola',
        'animations' => 'Animácie',
        'logs' => 'Logy',
        'stats' => 'Štatistiky',
    ],

    // Stránka animácií
    'animations_title' => 'Simulácie systémov',
    'animations_subtitle' => 'Interaktívne animácie dynamických systémov so synchronizovanými grafmi.',
    'select_system' => 'Vyberte systém',
    'target_position' => 'Cieľová poloha',
    'run_simulation' => 'Spustiť simuláciu',
    'reset_state' => 'Resetovať stav',
    'computing' => 'Počítam výsledky na serveri...',

    // Stránka logov
    'logs_title' => 'História požiadaviek',
    'logs_subtitle' => 'Všetky interakcie s CAS sú tu zaznamenané a dostupné na export do CSV.',
    'export_csv' => 'Exportovať do CSV',
    'status_success' => 'Úspech',
    'status_error' => 'Chyba',
    'no_logs' => 'Zatiaľ nie sú zaznamenané žiadne interakcie. Ak chcete generovať logy, choďte do Konzoly alebo Animácií.',

    // Stránka štatistík
    'stats_title' => 'Štatistiky používania',
    'stats_subtitle' => 'Prehľad používania simulácií všetkými používateľmi.',
    'unique_sessions' => 'Validované unikátne relácie',
    'recent_activity' => 'Podrobnosti o nedávnej aktivite',
    'time' => 'Čas',
    'animation' => 'Animácia',
    'location' => 'Lokalita',
    'no_stats' => 'Zatiaľ nebola zaznamenaná žiadna aktivita.',
    'location_unavailable' => 'Údaje o polohe nie sú k dispozícii',
];
