<?php

namespace justcoded\form2email\Mail;

use justcoded\form2email\Config\Config;
use justcoded\form2email\Mailer\PhpMailerSend;

class MailFactory
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getFactory()
    {
        $mailerId = $this->config->getMailerId();

        switch ($mailerId) {
            case Config::USE_PHPMAILER:
                return new PhpMailerSend($this->config);
        }

        throw new \Exception('Bad config');
    }
}