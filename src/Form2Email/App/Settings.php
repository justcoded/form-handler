<?php

namespace justcoded\form2email\AppConfig;

class Settings
{
    const USE_PHPMAILER = 1;

    const USE_POSTMARKAPP = 2;

    const USE_MANDRILL = 3;

    public static $validation  = [
        'rules' => [
            'name' => ['required'],
            'email' => ['required', 'email']
        ], // acoording to Valitron doc
        'labels' => '', // acoording to Valitron doc
        'messages' => '', // ability to overwrite rule messages
    ];

    public static $mailer = [
        'mailer' => self::USE_PHPMAILER, // (or USE_POSTMARKAPP, USE_MANDRILL)
        'host' => 'smtp.gmail.com',
        'user' => 'kos1985.dev@gmail.com',
        'pass' => '',
    ];

    public static $message = [
        'from' => ['email, name'],
        'to' => [], // can use tokens from input
        'cc' => [],
        'bcc' => [],
        'subject' => 'Contact form', // can use tokens from input
        'body' => '/../Template/template.php', // include of external file template.php, can use tokens from input
        'altBody' => '...',
    ];
}