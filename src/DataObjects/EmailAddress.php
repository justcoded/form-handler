<?php

namespace JustCoded\FormHandler\DataObjects;

/**
 * Class EmailAddress
 *
 * @package JustCoded\FormHandler\DataObjects
 */
class EmailAddress
{
	/**
	 * Email
	 *
	 * @var string
	 */
	protected $email;

	/**
	 * Name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * EmailAddress constructor.
	 *
	 * @param array $data Email data
	 *
	 * @throws \Exception Exception if data is blank.
	 */
	public function __construct(array $data)
	{
		if (empty($data)) {
			throw new \Exception('Email Address can\'t be blank');
		}

		if (! is_array($data)) {
			$data = [$data];
		}

		reset($data);
		if (is_numeric(key($data))) {
			$this->name  = '';
			$this->email = (string) current($data);
		} else {
			$this->email = (string) key($data);
			$this->name  = (string) current($data);
		}

		$this->name =  str_replace('"', '', trim($this->name));
		$this->email = trim($this->email);
	}

	/**
	 * Getting Email
	 *
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Getting Name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Getting formatted address
	 *
	 * @return string
	 */
	public function getFormattedAddress()
	{
		if ($this->name) {
			return "\"{$this->name}\" <{$this->email}>";
		} else {
			return "<{$this->email}>";
		}
	}
}