<?php

return [
    'default' => env('LARATHEME_DEFAULT', 'default'),
    'active' => env('LARATHEME_ACTIVE', 'default'),
    'paths' => [
        'views' => env('LARATHEME_VIEWS_PATH', 'resources/views/themes'),
        'assets' => env('LARATHEME_ASSETS_PATH', 'public/themes'),
        'stubs' => env('LARATHEME_STUBS_PATH', 'resources/stubs/laratheme'),
    ]
];