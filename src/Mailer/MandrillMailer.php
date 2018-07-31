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
	protected $apiKey;

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
			$mandrill = new Mandrill($this->apiKey);

			$mandrillMessage = array(
				'html'       => $message->getBody(),
				'subject'    => $message->getSubject(),
				'from_email' => $message->getFrom()->getEmail(),
				'from_name'  => $message->getFrom()->getName(),
			);

			if ($replyTo = $message->getReplyTo()) {
				$mandrillMessage['headers'] = [
					'Reply-To' => $replyTo->getEmail(),
				];
			}

			// Recipients.
			$recipients = [
				'to'  => $message->getTo(),
				'cc'  => $message->getCc(),
				'bcc' => $message->getBcc(),
			];

			$to = [];
			foreach ($recipients as $type => $emails) {
				if (empty($emails)) {
					continue;
				}
				foreach ($emails as $email) {
					$to[] = [
						'email' => $email->getEmail(),
						'name'  => $email->getName(),
						'type'  => $type,
					];
				}
			}

			$mandrillMessage['to'] = $to;

			// Attachments.
			if (0 < $message->getAttachmentsSize() && $message->getAttachmentsSize() < $this->attachmentsSizeLimit
				&& $attachments = $message->getAttachments()
			) {
				$attachmentsArray = [];
				foreach ($attachments as $attachment) {
					$attachmentsArray[] = [
						'type'    => $attachment->type,
						'name'    => $attachment->name,
						'content' => $attachment->getBase64(),
					];
				}

				$mandrillMessage['attachments'] = $attachmentsArray;
			}

			$result = $mandrill->messages->send($mandrillMessage);

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
