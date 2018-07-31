<?php

namespace JustCoded\FormHandler\Mailer;

use JustCoded\FormHandler\DataObjects\DataObject;
use JustCoded\FormHandler\DataObjects\MailMessage;
use PHPMailer\PHPMailer\PHPMailer as PHPMailerLib;
use PHPMailer\PHPMailer\Exception as PhpMailerException;

/**
 * Class PHPMailer
 *
 * @package JustCoded\FormHandler\Mailer
 */
class PHPMailer extends DataObject implements MailerInterface
{
	/**
	 * Attachments size limit
	 *
	 * @var int
	 */
	protected $attachmentsSizeLimit = 8000000;

	/**
	 * The user's host
	 *
	 * @var string
	 */
	protected $host;

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
	 * Protocol from user config
	 *
	 * @var string|bool
	 */
	protected $protocol = false;

	/**
	 * Port from user config
	 *
	 * @var string
	 */
	protected $port = 25;

	/**
	 * List of errors
	 *
	 * @var array
	 */
	protected $errors = array();

	/**
	 * Sending form
	 *
	 * @param MailMessage $message User message
	 *
	 * @return bool
	 */
	public function send(MailMessage $message)
	{
		$mail = new PHPMailerLib(true);               // Passing `true` enables exceptions.
		try {
			// Enable SMTP if host is set.
			if (! empty($this->host)) {
				$mail->SMTPDebug = 0;
				$mail->isSMTP();
				$mail->Host = $this->host;
				$mail->Port = $this->port;
				if (! empty($this->user)) {
					$mail->SMTPAuth = true;
					$mail->Username = $this->user;
					$mail->Password = $this->password;
				}
				if (! empty($this->protocol)) {
					$mail->SMTPSecure = $this->protocol;
				}
			}

			// Set From.
			if ($address = $message->getFrom()) {
				$mail->setFrom($address->getEmail(), $address->getName());
			}

			// Set Reply To.
			if ($replyTo = $message->getReplyTo()) {
				$mail->addReplyTo($replyTo->getEmail(), $replyTo->getName());
			}

			// Recipients.
			if ($to = $message->getTo()) {
				foreach ($to as $address) {
					$mail->addAddress($address->getEmail(), $address->getName());
				}

			}

			if ($cc = $message->getCc()) {
				foreach ($cc as $address) {
					$mail->addCC($address->getEmail(), $address->getName());
				}

			}

			if ($bcc = $message->getBcc()) {
				foreach ($bcc as $address) {
					$mail->addBCC($address->getEmail(), $address->getName());
				}

			}

			// Attachments.
			if (0 < $message->getAttachmentsSize() && $message->getAttachmentsSize() < $this->attachmentsSizeLimit
				&& $attachments = $message->getAttachments()
			) {
				foreach ($attachments as $attachment) {
					$mail->addAttachment($attachment->uploadPath, $attachment->name);
				}
			}

			// Content.
			$mail->Subject = $message->getSubject();
			if ($body = $message->getBody()) {
				$mail->isHTML(true);
				$mail->Body    = $message->getBody();
				$mail->AltBody = $message->getAltBody();
			} else {
				$mail->Body = $message->getAltBody();
			}

			$this->errors = array();

			return $mail->send();
		} catch (PhpMailerException $e) {
			$this->errors[] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;

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
