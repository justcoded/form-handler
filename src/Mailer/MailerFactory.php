<?php

namespace JustCoded\FormHandler\Mailer;

use JustCoded\FormHandler\Handlers\MailHandler;

/**
 * Class MailerFactory
 *
 * @package JustCoded\FormHandler\Mailer
 */
class MailerFactory
{
	/**
	 * Creating Mailer
	 *
	 * @param string $type Type mailer
	 * @param array $config Mailer config
	 *
	 * @return PHPMailer
	 */
	public static function create(string $type, array $config) {
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
