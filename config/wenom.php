<?php

return [
    'version' => '0.1.2',
    'schulnummer' => env('SCHULNUMMER'),
	'aes_password' => env( 'AES_PASSWORD'),
	'aes_salt' => env('AES_SALT'),
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
];
