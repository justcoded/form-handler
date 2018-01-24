<?php
namespace JustCoded\FormHandler\DataObjects;

class EmailAddress
{
	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var string
	 */
	protected $name;

	public function __construct($data)
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
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
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