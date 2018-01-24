<?php

namespace justcoded\form2email\Handler;

use justcoded\form2email\DataObject\DataObject;
use justcoded\form2email\Mailer\PhpHandlerSend;
use justcoded\form2email\Message\Message;

class MailHandler extends DataObject implements HandlerInterface
{
    const USE_PHPMAILER = 1;
    const USE_POSTMARKAPP = 2;
    const USE_MANDRILL = 3;

    /**
     * @return PhpHandlerSend
     * @throws \Exception
     */
    public function getMailer()
    {
        switch ($this->getMailerId()) {
            case self::USE_PHPMAILER:
                return new PhpHandlerSend($this->config);
        }

        throw new \Exception('Bad config');
    }

    /**
     * @param $data
     * @param Message $message
     */
    public function process($data, Message $message)
    {
        $this->getMailer()->send($data, $message);
    }
}
