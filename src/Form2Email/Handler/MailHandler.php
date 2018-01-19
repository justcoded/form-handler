<?php

namespace justcoded\form2email\Handler;

use justcoded\form2email\Mailer\MailerInterface;

class MailHandler
{
    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function process()
    {
        $this->mailer->process();
    }

}
