<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'api' => [
        'url' => env('API_URL', 'http://localhost:8000'),
        'local_token' => env('LOCAL_API_TOKEN', ''),
    ],
    'pt' => [
        'api_base_url' => env('API_URL'),
        'has_refresh_endpoint' => env('PT_HAS_REFRESH_ENDPOINT', false),
        'token_refresh_buffer_minutes' => env('PT_TOKEN_REFRESH_BUFFER_MINUTES', 5),
        'report_refresh_delay' => env('PT_REPORT_REFRESH_DELAY', 10),
        'teams_refresh_delay' => env('PT_TEAMS_REFRESH_DELAY', 60),
        'base_refresh_delay' => env('PT_BASE_REFRESH_DELAY', 120),
        'assigned_refresh_delay' => env('PT_ASSIGNED_REFRESH_DELAY', 30),
        'users_refresh_delay' => env('PT_USERS_REFRESH_DELAY', 60*24),
        'icon_url' => env('PT_ICON_URL'),
        'login_test' => env('PT_TEST', false),
    ],
    'mapbox' => [
        'token' => env('MAPBOX_TOKEN'),
        'style' => env('MAPBOX_STYLE', 'mapbox://styles/mapbox/outdoors-v12'),
        'track_interval' => env('MAPBOX_TRACK_INTERVAL_SECONDS', 1),
        'recenter_interval' => env('MAPBOX_RECENTER_UPDATES_COUNT', 20),
    ],


];
