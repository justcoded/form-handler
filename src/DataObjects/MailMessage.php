<?php

namespace JustCoded\FormHandler\DataObjects;

use function JustCoded\FormHandler\render_template;
use function JustCoded\FormHandler\value_to_string;

/**
 * Class MailMessage
 *
 * @package JustCoded\FormHandler\DataObjects
 */
class MailMessage extends DataObject
{

	/**
	 * Property with From email
	 *
	 * @var EmailAddress
	 */
	protected $from;

	/**
	 * Property with To email
	 *
	 * @var EmailAddress[]
	 */
	protected $to;

	/**
	 * Property with Reply-To email
	 *
	 * @var EmailAddress
	 */
	protected $replyTo;

	/**
	 * Property with Cc email
	 *
	 * @var EmailAddress[]
	 */
	protected $cc;

	/**
	 * Property with Bcc email
	 *
	 * @var EmailAddress[]
	 */
	protected $bcc;

	/**
	 * Property with Subject of email
	 *
	 * @var string
	 */
	protected $subject;

	/**
	 * Property with Body of email
	 *
	 * @var string
	 */
	protected $body;

	/**
	 * Property with AltBody of email
	 *
	 * @var string
	 */
	protected $altBody;

	/**
	 * Property with path of body template for email body
	 *
	 * @var string
	 */
	protected $bodyTemplate;

	/**
	 * Property with path of alt body template for email body
	 *
	 * @var string
	 */
	protected $altBodyTemplate;

	/**
	 * Name fields of submitted form
	 *
	 * @var array
	 */
	protected $tokens;

	/**
	 * List of attachments
	 *
	 * @var File[]|null
	 */
	protected $attachments = [];

	/**
	 * Message constructor.
	 *
	 * @param array $config User configs
	 */
	public function __construct(array $config)
	{
		parent::__construct($config);

		if ($this->from) {
			$this->from = new EmailAddress($this->from);
		}
		if ($this->replyTo) {
			$this->replyTo = new EmailAddress($this->replyTo);
		}

		// convert recipients to Data objects.
		foreach (array('to', 'cc', 'bcc') as $key) {
			if (!empty($this->$key)) {
				$addresses = [];
				foreach ($this->$key as $ind => $value) {
					$addresses[] = new EmailAddress([$ind => $value]);
				}
				$this->$key = $addresses;
			}
		}
	}

	/**
	 * Getting email From
	 *
	 * @return EmailAddress
	 */
	public function getFrom()
	{
		return $this->from;
	}

	/**
	 * Getting email To
	 *
	 * @return EmailAddress[]
	 */
	public function getTo()
	{
		return $this->to;
	}

	/**
	 * Getting email Reply To
	 *
	 * @return EmailAddress
	 */
	public function getReplyTo()
	{
		return $this->replyTo;
	}

	/**
	 * Getting email CC
	 *
	 * @return EmailAddress[]
	 */
	public function getCc()
	{
		return $this->cc;
	}

	/**
	 * Getting email Bcc
	 *
	 * @return EmailAddress[]
	 */
	public function getBcc()
	{
		return $this->bcc;
	}

	/**
	 * Getting email Subject
	 *
	 * @return string
	 */
	public function getSubject()
	{
		$subject = $this->subject;
		foreach ($this->tokens as $key => $value) {
			$subject = str_replace('{' . $key . '}', value_to_string($value), $subject);
		}
		return $subject;
	}

	/**
	 * Getting email Body Template
	 *
	 * @return string
	 */
	public function getBodyTemplate()
	{
		return $this->bodyTemplate;
	}

	/**
	 * Getting email Alt Body Template
	 *
	 * @return string
	 */
	public function getAltBodyTemplate()
	{
		return $this->altBodyTemplate;
	}

	/**
	 * Setting Tokens
	 *
	 * @param array $tokens Array of form field
	 */
	public function setTokens(array $tokens)
	{
		$this->tokens = $tokens;
	}

	/**
	 * Getting email Body
	 *
	 * @return string|null
	 */
	public function getBody()
	{
		if (!empty($this->body)) {
			return $this->body;
		} elseif (!empty($this->bodyTemplate)) {
			return render_template($this->bodyTemplate, $this->tokens);
		} else {
			return null;
		}
	}

	/**
	 * Getting alt body of email message
	 *
	 * @return string
	 */
	public function getAltBody()
	{
		if (!empty($this->altBody)) {
			return $this->altBody;
		} else {
			return render_template($this->altBodyTemplate, $this->tokens);
		}
	}

	/**
	 * Getting attachments files
	 *
	 * @return File[]|null
	 */
	public function getAttachments()
	{
		return $this->attachments;
	}

	/**
	 * Getting total size of attached files
	 */
	public function getAttachmentsSize()
	{
		if (empty($this->attachments)) {
			return 0;
		}

		$totalSize = 0;
		foreach ($this->attachments as $file) {
			$totalSize += $file->size;
		}

		return $totalSize;
	}

}
