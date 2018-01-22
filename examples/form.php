<?php

require __DIR__.'/../vendor/autoload.php';

use justcoded\form2email\App\App;
use justcoded\form2email\Handler\FormHandler;
use justcoded\form2email\Handler\MailHandler;


$mailer = App::getInstance()->getMailer();
$mailHandler = new MailHandler($mailer);
$formHandler = new FormHandler($mailHandler);

if ($formHandler->validate($_POST)) {
    $formHandler->process();
}

echo json_encode($formHandler->response());

