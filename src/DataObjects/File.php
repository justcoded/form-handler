<?php

namespace JustCoded\FormHandler\DataObjects;

/**
 * Class File
 *
 * @package JustCoded\FormHandler\DataObjects
 */
class File extends DataObject
{
	/**
	 * File name
	 *
	 * @var string
	 */
	public $name;

	/**
	 * File type
	 *
	 * @var string
	 */
	public $type;

	/**
	 * Temporary directory
	 *
	 * @var string
	 */
	public $tmp_name;

	/**
	 * Error
	 *
	 * @var int
	 */
	public $error;

	/**
	 * File size
	 *
	 * @var int
	 */
	public $size;

	/**
	 * Unique file name
	 *
	 * @var string
	 */
	public $uniqueName;

	/**
	 * Url path of file
	 *
	 * @var string
	 */
	public $uploadUrl;

	/**
	 * Upload directory of file
	 *
	 * @var string
	 */
	public $uploadPath;

	/**
	 * File constructor.
	 *
	 * @param array $config Array of file data
	 */
	public function __construct(array $config)
	{
		parent::__construct($config);

		$this->uniqueName = sha1(uniqid(mt_rand(), true)) . '.' . $this->getExtension();
	}

	/**
	 * Getting file extension
	 *
	 * @return mixed
	 */
	public function getExtension()
	{
		return pathinfo($this->name, PATHINFO_EXTENSION);
	}

	/**
	 * Represent image in base64 encode format
	 *
	 * @return string
	 */
	public function getBase64()
	{
		return base64_encode(file_get_contents($this->uploadPath));
	}

	/**
	 * Magic method for template converting
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->uploadUrl;
	}
}
