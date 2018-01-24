<?php

// enable errors =>  debug mode.
ini_set('display_errors', 1);
error_reporting(E_ALL);

// init autoload.
require __DIR__ . '/../vendor/autoload.php';

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
	'user'     => 'YOUR EMAIL',
	'password' => 'YOUR PASSWORD',
	'protocol' => 'tls',
	'port'     => 587,
];

$message = [
	'from'    => ['FROM EMAIL' => 'FROM NAME'],
	'to'      => ['TO EMAIL' => 'TO NAME'],
//	'cc'      => ['email' => 'name'],
//	'bcc'     => ['email' => 'name'],
	'subject' => 'Contact request from {name}',
	'bodyTemplate'    => __DIR__ . '/template-html.php',
	'altBodyTemplate' => __DIR__ . '/template-plain.php',
];


$mailer = new MailHandler($mailerConfig, new MailMessage($message));
$formHandler = new FormHandler($validation, $mailer);

if ($formHandler->validate($_POST)) {
	$formHandler->process();
}

echo json_encode($formHandler->response());

