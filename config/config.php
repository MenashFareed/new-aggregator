<?php

return [
    'database' => [
        'host' => getenv('DB_HOST'),
        'name' => getenv('DB_NAME'),
        'user' => getenv('DB_USER'),
        'pass' => getenv('DB_PASS'),
    ],
    'api' => [
        'news_api_key' => getenv('NEWS_API_KEY'),
    ],
    'app' => [
        'name' => 'News Aggregator',
        'url' => getenv('APP_URL'),
        'environment' => getenv('APP_ENV'),
    ]
]; 