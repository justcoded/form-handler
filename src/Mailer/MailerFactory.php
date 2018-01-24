<?php

namespace JustCoded\FormHandler\Mailer;

use JustCoded\FormHandler\Handlers\MailHandler;
use JustCoded\FormHandler\Mailer\PHPMailer;

class MailerFactory
{
	public static function create($type, $config) {
		switch ($type) {
			case MailHandler::USE_PHPMAILER:
				$mailer = new PHPMailer($config);
				break;
			default:
				new \Exception("MailerFactory: unable to find mailer for type \"{$type}\"");
		}

		return $mailer;
	}
}