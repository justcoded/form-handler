<?php
namespace JustCoded\FormHandler\DataObjects;

class EmailAttachment
{
	/**
	 * Path of file attachments
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * Name of file attachments
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * EmailAttachment constructor.
	 *
	 * @param array $data Email attachments data
	 *
	 * @throws \Exception Exception.
	 */
	public function __construct(array $data)
	{
		if (empty($data)) {
			throw new \Exception('Attachments can\'t be blank');
		}

		if (! is_array($data)) {
			$data = [$data];
		}

		reset($data);
		if (is_numeric(key($data))) {
			$this->name  = '';
			$this->path = (string) current($data);
		} else {
			$this->path = (string) key($data);
			$this->name  = (string) current($data);
		}

		$this->name =  str_replace('"', '', trim($this->name));
		$this->path = trim($this->path);
	}

	/**
	 * Getting path of attachments
	 *
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Getting name of attachments
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

}