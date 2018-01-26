<?php

// enable errors =>  debug mode.
ini_set('display_errors', 1);
error_reporting(E_ALL);

// init autoload.
require __DIR__ . '/../vendor/autoload.php';

use JustCoded\FormHandler\FileManager\FileManager;
use JustCoded\FormHandler\FormHandler;
use JustCoded\FormHandler\Handlers\MailHandler;
use JustCoded\FormHandler\DataObjects\MailMessage;

$validation = [
	'rules'  => [
		'required' => [
			'fields'  => ['name', 'email', 'subject', 'message'],
			'message' => '{field} is required'
		],
		'email'    => [
			'fields'  => ['email'],
			'message' => '{field} is not a valid email address'
		],
        'file' => [
            'fields' => ['cv_file', 'image_file'],
            'allowType' => ['jpeg', 'jpg', 'pdf', 'png'],
            'allowSize' => 10000000 //10 MB
        ]
	], // acoording to Valitron doc.
	'labels' => [
		'name'  => 'Name',
		'email' => 'Email address'
	] // acoording to Valitron doc.
];

$mailerConfig = [
	'mailer'   => MailHandler::USE_PHPMAILER, // (or USE_POSTMARKAPP, USE_MANDRILL)
	'host'     => 'smtp.gmail.com',
	'user'     => 'kos1985.dev@gmail.com',
	'password' => '',
	'protocol' => 'tls',
	'port'     => 587,
];

$message = [
	'from'    => ['hello@justcoded.co.uk' => 'FROM NAME'],
	'to'      => ['kostant21@yahoo.com' => 'TO NAME'],
//	'cc'      => ['email' => 'name'],
//	'bcc'     => ['email' => 'name'],
	'subject' => 'Contact request from {name}',
	'bodyTemplate'    => __DIR__ . '/template-html.php',
	'altBodyTemplate' => __DIR__ . '/template-plain.php',
    'attachments' => FileManager::prepareUpload([
        'cv_file', 'image_file'
    ])
];


$mailer = new MailHandler($mailerConfig, new MailMessage($message));
$formHandler = new FormHandler($validation, $mailer);

if ($formHandler->validate($_POST)) {
	$formHandler->process();
}

echo json_encode($formHandler->response());

