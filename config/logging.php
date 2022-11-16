<?php

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single','singleInfo','singleAlert','singleWarning','singleWarning','singleCritical','singleEmergency','activity'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        /* ******** Custom Log ********* */
        'activity' => [
            'driver' => 'daily',
            'path' => storage_path('logs/activity.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'singleInfo' => [
            'driver' => 'daily',
            'path' => storage_path('logs/info.log'),
            'level' => 'info',
            'days' => 14,
        ],
        'singleAlert' => [
            'driver' => 'daily',
            'path' => storage_path('logs/alert.log'),
            'level' => 'alert',
            'days' => 14,
        ],
        'singleWarning' => [
            'driver' => 'daily',
            'path' => storage_path('logs/warning.log'),
            'level' => 'warning',
            'days' => 14,
        ],
        'singleCritical' => [
            'driver' => 'daily',
            'path' => storage_path('logs/critical.log'),
            'level' => 'critical',
            'days' => 14,
        ],
        'singleEmergency' => [
            'driver' => 'daily',
            'path' => storage_path('logs/emergency.log'),
            'level' => 'emergency',
            'days' => 14,
        ],
        /* ******** Custom Log ********* */
        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],
    ],

];
