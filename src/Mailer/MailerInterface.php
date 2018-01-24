<?php
namespace JustCoded\FormHandler\Mailer;

use JustCoded\FormHandler\DataObjects\MailMessage;

interface MailerInterface
{
	public function send(MailMessage $message);

	public function getErrors();
}