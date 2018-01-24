<?php

namespace justcoded\form2email\Handler;


use justcoded\form2email\Message\Message;

interface HandlerInterface
{
    public function process($formFields, Message $message);
}