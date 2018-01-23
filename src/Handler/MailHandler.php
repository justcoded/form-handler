<?php

namespace justcoded\form2email\Handler;

use justcoded\form2email\Mailer\PhpHandlerSend;
use justcoded\form2email\Message\Message;

class MailHandler implements HandlerInterface
{
    const USE_PHPMAILER = 1;
    const USE_POSTMARKAPP = 2;
    const USE_MANDRILL = 3;

    protected $config;
    protected $message;

    public function __construct(array $config, Message $message)
    {
        $this->config = $config;
        $this->message = $message;
    }

    public function getMailer()
    {
        $mailerId = $this->config['mailer'];

        switch ($mailerId) {
            case self::USE_PHPMAILER:
                return new PhpHandlerSend($this->config, $this->message);
        }

        throw new \Exception('Bad config');
    }

    public function process($data)
    {
        $this->getMailer()->send($data);
    }
}
