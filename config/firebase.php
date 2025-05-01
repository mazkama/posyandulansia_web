<?php

return [
    'credentials' => env('FIREBASE_CREDENTIALS', storage_path('firebase_credentials.json')),

    'database' => [
        'url' => env('FIREBASE_DATABASE_URL'),
    ],

    'auth' => [
        'default_uid' => env('FIREBASE_AUTH_UID'),
    ],

    'storage' => [
        'default_bucket' => env('FIREBASE_STORAGE_BUCKET'),
    ],

    'messaging' => [
        'topic' => env('FIREBASE_MESSAGING_TOPIC', 'default_topic'),
    ],
];
