<?php

namespace justcoded\form2email\Handler;

use justcoded\form2email\Mailer\PhpMailerSend;
use justcoded\form2email\Message\Message;

class MailHandler
{
    const USE_PHPMAILER = 1;
    const USE_POSTMARKAPP = 2;
    const USE_MANDRILL = 3;

    protected $config;
    protected $message;

    public function __construct($config, Message $message)
    {
        $this->config = $config;
        $this->message = $message;
    }

    public function getMailer()
    {
        $mailerId = $this->config['mailer'];

        switch ($mailerId) {
            case self::USE_PHPMAILER:
                return new PhpMailerSend($this->config, $this->message);
        }

        throw new \Exception('Bad config');
    }

}
