<?php

return [
    'version' => '0.1.2',

    'npm' => '0.7.7',
    'schulnummer' => env(key: 'SCHULNUMMER'),
	'aes_password' => env(key: 'AES_PASSWORD'),
	'aes_salt' => env(key: 'AES_SALT'),
    'mail_send_credentials' => [
        'mailer' => env('MAIL_MAILER'),
        'host' => env('MAIL_HOST'),
        'port' => env('MAIL_PORT'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'encryption' => env('MAIL_ENCRYPTION'),
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
    ],
    'filters' => [
        'meinunterricht' => [
            'teilleistungen' => env('FILTERS_MEINUNTERRICHT_TEILLEISTUNGEN', false),
            'mahnungen' => env('FILTERS_MEINUNTERRICHT_MAHNUNGEN', true),
            'fehlstunden' => env('FILTERS_MEINUNTERRICHT_FEHLSTUNDEN', false),
            'bemerkungen' => env('FILTERS_MEINUNTERRICHT_BEMERKUNGEN', true),
            'kurs' => env('FILTERS_MEINUNTERRICHT_KURS', true),
            'note' => env('FILTERS_MEINUNTERRICHT_NOTE', true),
            'fach' => env('FILTERS_MEINUNTERRICHT_FACH', true),
        ],
        'leistungsdatenuebersicht' => [
            'teilleistungen' => env('FILTERS_LEISTUNGSDATENUEBERSICHT_TEILLEISTUNGEN', false),
            'fachlehrer' => env('FILTERS_LEISTUNGSDATENUEBERSICHT_FACHLEHRER', true),
            'mahnungen' => env('FILTERS_LEISTUNGSDATENUEBERSICHT_MAHNUNGEN', false),
            'bemerkungen' => env('FILTERS_LEISTUNGSDATENUEBERSICHT_BEMERKUNGEN', true),
        ],
    ],
];
