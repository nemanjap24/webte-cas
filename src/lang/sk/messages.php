<?php

return [
    'badge' => 'Semestrálny projekt • CAS + REST API',
    'title' => 'Riadenie dynamických systémov vo webovej aplikácii',
    'subtitle' => 'Jedna stránka pre spúšťanie výpočtov, vizualizáciu grafov a animácie systémov: inverzné kyvadlo a gulička na tyči.',
    'ctaPrimary' => 'Otvoriť výpočtový modul',
    'ctaSecondary' => 'Referenčný model (CTMS)',
    'sectionsTitle' => 'Kľúčové časti zadania',
    'cards' => [
        [
            'title' => 'CAS API vrstva',
            'text' => 'Backend bude pripájať Octave (alebo iný CAS) cez REST API. Výpočty pre animáciu a graf budú bežať na serveri.',
        ],
        [
            'title' => '2 simulácie',
            'text' => 'Implementujú sa modely: inverzné kyvadlo a gulička na tyči. Graf a animácia musia byť časovo synchronizované.',
        ],
        [
            'title' => 'Bezpečnosť + logovanie',
            'text' => 'Volania CAS budú chránené tokenom/API kľúčom. Každý request sa bude logovať a bude exportovateľný do CSV.',
        ],
        [
            'title' => 'Dokumentácia + Docker',
            'text' => 'OpenAPI dokumentácia (aj PDF export) a kontajnerizácia celej aplikácie pomocou Dockeru.',
        ],
    ],
    'footer' => 'Prvá obrazovka je pripravená. Ďalej môžeme spraviť formulár výpočtov alebo prvú animáciu.',

    // Console page translations
    'console_title' => 'Výpočtový modul (napojený na backend)',
    'console_subtitle' => 'Požiadavky už idú cez backend mutation. Výstupy sa logujú na serveri a premenné zostávajú uložené pre daný session token.',
    'sessionToken' => 'Session token',
    'apiKey' => 'API kľúč',
    'run' => 'Spustiť príkazy',
    'clearScript' => 'Vymazať skript',
    'loadBall' => 'Ukážka: gulička na tyči',
    'loadPendulum' => 'Ukážka: inverzné kyvadlo',
    'lastBatch' => 'Posledný batch výstupov',
    'outputHistory' => 'História výstupov (logy)',
    'vars' => 'Uložené premenné relácie',
    'back' => 'Späť na úvod',
];
