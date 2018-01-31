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
	 * Create mailer depends on the type config
	 *
	 * @param string $type Type of Mailer
	 * @param array $config Mailer config
	 *
	 * @return MandrillMailer|PHPMailer
	 */
	public static function create(string $type, array $config)
	{
		switch ($type) {
			case MailHandler::USE_PHPMAILER:
				$mailer = new PHPMailer($config);
				break;
			case MailHandler::USE_MANDRILL:
				$mailer = new MandrillMailer($config);
				break;
			default:
				new \Exception("MailerFactory: unable to find mailer for type \"{$type}\"");
		}

		return $mailer;
	}
}
