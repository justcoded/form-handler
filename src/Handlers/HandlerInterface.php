<?php

namespace JustCoded\FormHandler\Handlers;

interface HandlerInterface
{
    public function process(array $formFields);

    public function getErrors();
}