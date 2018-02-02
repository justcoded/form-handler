<?php

namespace JustCoded\FormHandler\FileManager;

use JustCoded\FormHandler\DataObjects\DataObject;
use JustCoded\FormHandler\DataObjects\File;

/**
 * Class FileManager
 *
 * @package JustCoded\FormHandler\FileManager
 */
class FileManager extends DataObject
{
	/**
	 * Upload server directory
	 *
	 * @var string
	 */
	protected $uploadPath;

	/**
	 * Upload url directory
	 *
	 * @var string
	 */
	protected $uploadUrl;

	/**
	 * Uploading files to the server
	 *
	 * @param array $fields Form fields
	 *
	 * @return array
	 */
	public function upload(array $fields)
	{
		if (!is_dir($this->uploadPath)) {
			mkdir($this->uploadPath, 0777, true);
		}

		$files = [];
		foreach ($fields as $field) {
			if (array_key_exists($field, $_FILES)) {
				$fileField = $_FILES[$field];
				$file = new File($fileField);

				$name = preg_replace('/[^a-z0-9\-\_\.]+/iu', '', trim($file->name));
				$name = preg_replace('/(\.[a-z0-9]+)$/i', '', $name);
				$path = realpath($this->uploadPath) . '/' . $name . '-' . $file->uniqueName;

				if ($file->error == 0 && move_uploaded_file($file->tmp_name, $path)) {
					$file->uploadUrl = $this->uploadUrl . '/' . $name . '-' . $file->uniqueName;
					$file->uploadPath = $path;
					$_POST[$field] = $file;
					$files[] = $file;
				}
			}
		}

		return $files;
	}
}
