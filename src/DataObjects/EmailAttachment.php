<?php
namespace JustCoded\FormHandler\DataObjects;

class EmailAttachment
{
	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @var string
	 */
	protected $name;

	public function __construct($data)
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
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

}