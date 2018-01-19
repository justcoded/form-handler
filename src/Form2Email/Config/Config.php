<?php

namespace justcoded\form2email\Config;

class Config
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
        'host' => '',
        'user' => '',
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

    public function getMailerId()
    {
        return self::$mailer['mailer'];
    }

    public function getValidation()
    {
        return self::$validation;
    }

    public function getValidationRules()
    {
        return self::getValidation()['rules'];
    }

    public function getMailerHost()
    {
        return self::$mailer['host'];
    }

    public function getMailerUser()
    {
        return self::$mailer['user'];
    }

    public function getMailerPass()
    {
        return self::$mailer['pass'];
    }

    public function getMessageSubject()
    {
        return self::$message['subject'];
    }

    public function getMessageTemplate()
    {
        return self::$message['body'];
    }
}