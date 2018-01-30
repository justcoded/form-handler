<?php

namespace JustCoded\FormHandler\Handlers;

use JustCoded\FormHandler\DataObjects\MailMessage;
use JustCoded\FormHandler\Mailer\MailerFactory;
use JustCoded\FormHandler\Mailer\MailerInterface;

/**
 * Class MailHandler
 *
 * @package JustCoded\FormHandler\Handlers
 */
class MailHandler implements HandlerInterface
{
	const USE_PHPMAILER = 'PhpMailer';
	const USE_POSTMARKAPP = 'PostMarkApp';
	const USE_MANDRILL = 'Mandrill';

	/**
	 * Mailer created by MailerFactory
	 *
	 * @var MailerInterface
	 */
	protected $mailer;

	/**
	 * Object MailMessage
	 *
	 * @var MailMessage;
	 */
	protected $message;

	/**
	 * MailHandler constructor.
	 *
	 * @param array $config User configs
	 * @param MailMessage $message Mail message object
	 *
	 * @throws \Exception Error if bad config.
	 */
	public function __construct(array $config, MailMessage $message)
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
	 * This method is run if the form passed validation
	 *
	 * @param array $data Form fields
	 *
	 * @return bool
	 */
	public function process(array $data)
	{
		$this->message->setTokens($data);
		$this->mailer->send($this->message);

		return true;
	}

	/**
	 * Getting errors from Mailer
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->mailer->getErrors();
	}
}
