<?php

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
		'message' => [
			'required',
			['lengthMin', 5]
		],
		'cv' => [  // this is file field.
			[
				'required',
				'message' => 'Please upload {field}',
			],
			[
				'file',
				['jpeg', 'jpg', 'png', 'pdf'], // types.
				2000000,                       // size limit 2 MB.
				'message' => '{field} should be up to 2MB and allows only file types jpeg, png.',
			],
		],
		'links.*' => ['url'],
	], // according to Valitron doc for mapFieldsRules.
	'labels' => [
		'name'  => 'Name',
		'email' => 'Email address',
		'message' => 'About you',
		'cv' => 'CV',
		'links.*' => 'Links',
	] // according to Valitron doc.
];

// Mandrill config.
$mailerConfig = [
	'mailer'   => MailHandler::USE_PHPMAILER,
	'host'     => 'SMTP HOST',     // set your smtp host.
	'user'     => 'YOUR EMAIL',    // set email.
	'password' => 'YOUR PASSWORD', // set password.
	'protocol' => 'tls',           // 'tls', 'ssl' or FALSE for not secure protocol/
	'port'     => 587,             // your port.

	'attachmentsSizeLimit' => 8000000, // around 8MB.
];

// File manager config.
$fileManager = new FileManager([
	'uploadPath' => __DIR__ . '/attachments',
	'uploadUrl' => 'http://MY-DOMAIN.COM/attachments',
]);

$message = [
	'from' => ['FROM.EMAIL@DOMAIN.COM' => 'FROM NAME'],     // set correct FROM.
	'to' => ['TO.EMAIL@DOMAIN.COM' => 'TO NAME'],           // set correct TO.
	'cc'      => ['CC@DOMAIN.COM' => 'CC NAME'],
	'bcc'     => ['BCC@DOMAIN.COM'],

	'subject' => 'Contact request from {name}',
	'bodyTemplate' => __DIR__ . '/template-html.php',
	'altBodyTemplate' => __DIR__ . '/template-plain.php',
	'attachments' => $fileManager->upload([
		'cv',
	])
];


$mailer = new MailHandler($mailerConfig, new MailMessage($message));
$formHandler = new FormHandler($validation, $mailer);

if ($formHandler->validate($_POST)) {
	$formHandler->process();
}

// write errors and return back.
setcookie('advanced_response', $formHandler->response());
header('Location: index.php');
exit;
