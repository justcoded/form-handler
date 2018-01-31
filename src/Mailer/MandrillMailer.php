<?php

namespace JustCoded\FormHandler\Mailer;

use JustCoded\FormHandler\DataObjects\DataObject;
use JustCoded\FormHandler\DataObjects\EmailAttachment;
use JustCoded\FormHandler\DataObjects\MailMessage;
use Mandrill;
use Mandrill_Error;

/**
 * Class MandrillMailer
 *
 * @package JustCoded\FormHandler\Mailer
 */
class MandrillMailer extends DataObject implements MailerInterface
{
	/**
	 * Attachments size limit
	 *
	 * @var int
	 */
	protected $attachmentsSizeLimit = 8000000;

	/**
	 * User from user config
	 *
	 * @var string
	 */
	protected $user;

	/**
	 * Password from user config
	 *
	 * @var string
	 */
	protected $password;

	/**
	 * List of errors
	 *
	 * @var array
	 */
	protected $errors = array();

	/**
	 *  Sending form
	 *
	 * @param MailMessage $message Mailer message
	 *
	 * @return array|bool
	 */
	public function send(MailMessage $message)
	{
		try {
			$mandrill = new Mandrill($this->password);

			$mandrillMessage = array(
				'html' => $message->getBody(),
				'text' => 'Example text content',
				'subject' => $message->getSubject(),
				'from_email' => $message->getFrom()->getEmail(),
				'from_name' => $message->getFrom()->getName(),
			);

			// Recipients.
			if ($to = $message->getTo()) {
				$toArray = [];
				foreach ($to as $address) {
					$toArray[] = [
						'email' => $address->getEmail(),
						'name' => $address->getName(),
						'type' => 'to'
					];
				}

				if ($cc = $message->getCc()) {
					foreach ($cc as $address) {
						$toArray[] = [
							'email' => $address->getEmail(),
							'name' => $address->getName(),
							'type' => 'cc'
						];
					}
				}

				if ($bcc = $message->getBcc()) {
					foreach ($bcc as $address) {
						$toArray[] = [
							'email' => $address->getEmail(),
							'name' => $address->getName(),
							'type' => 'bcc'
						];
					}
				}

				$mandrillMessage['to'] = $toArray;
			}

			// Attachments.
			if (0 < $message->getAttachmentsSize() && $message->getAttachmentsSize() < $this->attachmentsSizeLimit
				&& $attachments = $message->getAttachments()
			) {
				$attachmentsArray = [];
				foreach ($attachments as $attachment) {
					$attachmentsArray[] = [
						'type' => $attachment->type,
						'name' => $attachment->name,
						'content' => $attachment->getBase64()
					];
				}

				$mandrillMessage['attachments'] = $attachmentsArray;
			}

			$async = false;
			$ip_pool = 'Main Pool';
			$send_at = date('Y-m-d h:i:s');
			$result = $mandrill->messages->send($mandrillMessage, $async, $ip_pool, $send_at);

			return $result;
		} catch (Mandrill_Error $e) {
			$this->errors[] = 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
			return false;
		}
	}

	/**
	 * Getting list of errors
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}
}