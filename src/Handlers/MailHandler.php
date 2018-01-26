<?php

namespace JustCoded\FormHandler\Handlers;

use JustCoded\FormHandler\DataObjects\MailMessage;
use JustCoded\FormHandler\Mailer\MailerFactory;
use JustCoded\FormHandler\Mailer\MailerInterface;

class MailHandler implements HandlerInterface
{
	const USE_PHPMAILER = 'PhpMailer';
	const USE_POSTMARKAPP = 'PostMarkApp';
	const USE_MANDRILL = 'Mandrill';

	/**
	 * @var MailerInterface
	 */
	protected $mailer;

	/**
	 * @var MailMessage;
	 */
	protected $message;

	public function __construct($config, MailMessage $message)
	{
		if (empty($config['mailer'])) {
			throw new \Exception('MailHandler config should specify "mailer" type.');
		}

		$type = $config['mailer'];
		unset($config['mailer']);
		$this->mailer = MailerFactory::create($type, $config);
		$this->message = $message;
	}

	/**
	 * @param array $data
	 */
	public function process(array $data)
	{
		$this->message->setTokens($data);
        $this->message->setFiles();
		$this->mailer->send($this->message);
	}

	public function getErrors()
	{
		return $this->mailer->getErrors();
	}
}
