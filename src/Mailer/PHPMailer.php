<?php

namespace JustCoded\FormHandler\Mailer;

use JustCoded\FormHandler\DataObjects\DataObject;
use JustCoded\FormHandler\DataObjects\EmailAttachment;
use JustCoded\FormHandler\DataObjects\MailMessage;
use PHPMailer\PHPMailer\PHPMailer as PHPMailerLib;
use PHPMailer\PHPMailer\Exception as PhpMailerException;

class PHPMailer extends DataObject implements MailerInterface
{
	/**
	 * @var string
	 */
	protected $host;

	/**
	 * @var string
	 */
	protected $user;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @var string|bool
	 */
	protected $protocol = false;

	/**
	 * @var string
	 */
	protected $port = 25;

	/**
	 * @var array
	 */
	protected $errors = array();

	public function send(MailMessage $message)
	{
		$mail = new PHPMailerLib(true);               // Passing `true` enables exceptions.
		try {
			// Enable SMTP if host is set.
			if ( ! empty($this->host)) {
				$mail->SMTPDebug = 0;
				$mail->isSMTP();
				$mail->Host = $this->host;
				$mail->Port = $this->port;
				if ( ! empty($this->user)) {
					$mail->SMTPAuth = true;
					$mail->Username = $this->user;
					$mail->Password = $this->password;
				}
				if ( ! empty($this->protocol)) {
					$mail->SMTPSecure = $this->protocol;
				}
			}

			// Set From.
			if ($address = $message->getFrom()) {
				$mail->setFrom($address->getEmail(), $address->getName());
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

            //Attachments
            if ($attachments = $message->getFiles()) {
                foreach ($attachments as $attachment) {
                    /** @var EmailAttachment $attachment */
                    $mail->addAttachment($attachment->getPath(), $attachment->getName());    // Optional name
                }
            }

			// Content.
			$mail->Subject = $message->getSubject();
			if ($body = $message->getBody()) {
				$mail->isHTML(true);
				$mail->Body    = $message->getBody();
				$mail->AltBody = $message->getAltBody();
			} else {
				$mail->Body    = $message->getAltBody();
			}

			$this->errors = array();
			return $mail->send();
		} catch (PhpMailerException $e) {
			$this->errors[] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
			return false;
		}
	}

	public function getErrors()
	{
		return $this->errors;
	}
}