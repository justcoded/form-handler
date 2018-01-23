<?php

require __DIR__.'/../vendor/autoload.php';

use justcoded\form2email\Handler\FormHandler;
use justcoded\form2email\Handler\MailHandler;
use justcoded\form2email\Message\Message;

$validation  = [
    'rules' => [
        'required' => [
            'fields' => ['name', 'email'],
            'message' => '{field} is required'
        ],
        'email' => [
            'fields' => ['email'],
            'message' => '{field} is not a valid email address'
        ]
    ], // acoording to Valitron doc
    'labels' => [
        'name' => 'Name',
        'email' => 'Email address'
    ] // acoording to Valitron doc
];

$mailerConfig = [
    'mailer' => MailHandler::USE_PHPMAILER, // (or USE_POSTMARKAPP, USE_MANDRILL)
    'host' => 'smtp.gmail.com',
    'user' => 'kos1985.dev@gmail.com',
    'pass' => '',
];

$message = [
    'from' => ['kostant21@yahoo.com', 'kosFrom'],
    'to' => ['kostant21@yahoo.com', 'kosTo'], // can use tokens from input
    'cc' => ['kostant21@yahoo.com', 'kosCc'],
    'bcc' => ['kostant21@yahoo.com', 'kosBcc'],
    'subject' => 'Contact form', // can use tokens from input
    'body' => 'template.php', // include of external file template.php, can use tokens from input
    'altBody' => '...',
];


$mailerHandler = new MailHandler($mailerConfig, new Message($message));

$formHandler = new FormHandler($validation, $mailerHandler->getMailer());

if ($formHandler->validate($_POST)) {
    $formHandler->process();
}

echo json_encode($formHandler->response());

