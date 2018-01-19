<?php

require __DIR__.'/../../vendor/autoload.php';


use justcoded\form2email\Config\Config;
use justcoded\form2email\Handler\FormHandler;
use justcoded\form2email\Handler\MailHandler;
use justcoded\form2email\Mail\MailFactory;


/*$validation = [
    'rules' => '', // acoording to Valitron doc
    'labels' => '', // acoording to Valitron doc
    'messages' => '', // ability to overwrite rule messages
];

$mailer = [
    Config::USE_PHPMAILER, // (or USE_POSTMARKAPP, USE_MANDRILL)
    'host' => '',
    'user' => '',
    'pass' => '',
];

$message = [
    'from' => ['email, name'],
    'to' => [], // can use tokens from input
    'cc' => [],
    'bcc' => [],
    'subject' => '...', // can use tokens from input
    'body' => '...', // include of external file template.php, can use tokens from input
    'altBody' => '...',
];*/
$message = '';
$config = new Config();
$mailerFactory = new MailFactory($config);
$mailer = $mailerFactory->getFactory();
$mailHandler = new MailHandler($mailer);
$formHandler = new FormHandler($config->getValidation(), $mailHandler);

if ($formHandler->validate($_POST)) {
    $formHandler->process();
}

echo json_encode($formHandler->response());

