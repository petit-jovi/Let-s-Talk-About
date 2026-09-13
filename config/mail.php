<?php

return [

    'default' => env('MAIL_MAILER', 'log'),

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME'),
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => ['smtp', 'log'],
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => ['smtp', 'log'],
        ],

    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'no-reply@letstalkabout.local'),
        'name' => env('MAIL_FROM_NAME', "Let's Talk About"),
    ],

    /*
     * Adresses du Bureau Executif notifiees a chaque nouvelle demande d'adhesion.
     * Voir LTA-SequenceDiagram-Soumission&Validation_Adhesion : message 6
     * "Notification email (Nouvelle demande)".
     */
    'lta_bureau_notification_emails' => array_filter(array_map(
        'trim',
        explode(',', env('LTA_BUREAU_NOTIFICATION_EMAILS', ''))
    )),

];
