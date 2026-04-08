<?php

return [
    'holiday_api' => [
        'base_url' => env('HOLIDAY_API_BASE_URL', 'https://date.nager.at/api/v3'),
        'default_country' => env('HOLIDAY_DEFAULT_COUNTRY', 'PL'),
        'cache_ttl' => 60 * 60 * 24,
    ],
    'task' => [
        'default_status' => 'new',
        'default_priority' => 'medium',
    ],
];
