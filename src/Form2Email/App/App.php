<?php

namespace justcoded\form2email\App;

use justcoded\form2email\AppConfig\Settings;
use justcoded\form2email\Mailer\PhpMailerSend;

require_once ('Settings.php');

class App
{
    private static $instance;
    private static $mailer;

    private function __construct()
    {
        $this->init();
    }

    private function init()
    {
        switch (App::getMailerId()) {
            case Settings::USE_PHPMAILER:
                $this->mailer = new PhpMailerSend();
                break;
        }
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getMailer()
    {
        return $this->mailer;
    }

    public static function getMailerId()
    {
        return Settings::$mailer['mailer'];
    }

    public static function getValidation()
    {
        return Settings::$validation;
    }

    public static function getValidationRules()
    {
        return Settings::$validation['rules'];
    }

    public static function getMailerHost()
    {
        return Settings::$mailer['host'];
    }

    public static function getMailerUser()
    {
        return Settings::$mailer['user'];
    }

    public static function getMailerPass()
    {
        return Settings::$mailer['pass'];
    }

    public static function getMessageSubject()
    {
        return Settings::$message['subject'];
    }

    public static function getMessageTemplate()
    {
        return Settings::$message['body'];
    }
}