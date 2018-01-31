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
	'fields' => [
		'name' => ['required'],
		'email' => ['required', 'email'],
		'subject' => ['required'],
		'message' => [
			'required',
			['lengthMin', 5]
		],
		'cv_file' => [
			[
				'required',
				'message' => 'Please upload {field}',
			],
			[
				'file',
				['jpeg', 'jpg', 'png'], // types.
				2000000, // size limit 2 MB.
				'message' => '{field} should be up to 2MB and allows only file types jpeg, png.',
			],
		],
	], // according to Valitron doc for mapFieldsRules.
	'labels' => [
		'name'  => 'Name',
		'email' => 'Email address'
	] // according to Valitron doc.
];

// Mandrill config.
$mailerConfig = [
	'mailer'   => MailHandler::USE_MANDRILL, // (or USE_POSTMARKAPP, USE_MANDRILL)
	'apiKey' => '_5mPSvb39BQqnA7G_dOaAA',
	'attachmentsSizeLimit' => 8000000, // around 8MB.
];

$fileManager = new FileManager([
	'uploadPath' => __DIR__ . '/attachments',
	'uploadUrl' => 'http://MY-DOMAIN.COM/attachments',
]);

$message = [
	'from' => ['hello@justcoded.co.uk' => 'FROM NAME'],
	'to' => ['kostant21@yahoo.com' => 'TO NAME'],
//	'cc'      => ['email' => 'name'],
//	'bcc'     => ['email' => 'name'],
	'subject' => 'Contact request from {name}',
	'bodyTemplate' => __DIR__ . '/template-html.php',
	'altBodyTemplate' => __DIR__ . '/template-plain.php',
	'attachments' => $fileManager->upload([
		'cv_file', 'image_file'
	])
];


$mailer = new MailHandler($mailerConfig, new MailMessage($message));
$formHandler = new FormHandler($validation, $mailer);

if ($formHandler->validate($_POST)) {
	$formHandler->process();
}

echo json_encode($formHandler->response());
