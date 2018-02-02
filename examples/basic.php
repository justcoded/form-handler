<?php

// init autoload.
require __DIR__ . '/../vendor/autoload.php';

use JustCoded\FormHandler\FormHandler;
use JustCoded\FormHandler\Handlers\MailHandler;
use JustCoded\FormHandler\DataObjects\MailMessage;

$validationRules = [
	'fields' => [
		'name' => ['required'],
		'email' => ['required', 'email'],
		'message' => [
			'required',
			['lengthMin', 5]
		],
	], // according to Valitron doc for mapFieldsRules.
	'labels' => [
		'name'  => 'Name',
		'email' => 'Email address',
		'message' => 'Message',
	] // according to Valitron doc.
];

// SMTP config.
$mailerConfig = [
	'mailer'   => MailHandler::USE_PHPMAILER,
	'host'     => 'SMTP HOST',     // set your smtp host.
	'user'     => 'YOUR EMAIL',    // set email.
	'password' => 'YOUR PASSWORD', // set password.
	'protocol' => 'tls',           // 'tls', 'ssl' or FALSE for not secure protocol/
	'port'     => 587,             // your port.
];

// Message settings.
$messageConfig = [
	'from' => ['FROM.EMAIL@DOMAIN.COM' => 'FROM NAME'],     // set correct FROM.
	'to' => ['TO.EMAIL@DOMAIN.COM' => 'TO NAME'],           // set correct TO.
	'subject' => 'Contact request from {name}',
	'bodyTemplate' => __DIR__ . '/template-html.php',
	'altBodyTemplate' => __DIR__ . '/template-plain.php',
];

// Run processing.
$mailer = new MailHandler($mailerConfig, new MailMessage($messageConfig));
$form   = new FormHandler($validationRules, $mailer);

if ($form->validate($_POST)) {
	$form->process();
}

// write errors and return back.
setcookie('basic_response', $form->response());
header('Location: index.php');
exit;
