<?php

namespace JustCoded\FormHandler\Validator;

use Valitron\Validator;
use JustCoded\FormHandler\DataObjects\File;

/**
 * Class File
 *
 * @package JustCoded\FormHandler\Validator
 */
class FileValidator
{
	/**
	 * Custom Valitron file attachments validation
	 *
	 * @param Validator $v Validator object
	 *
	 * @return Validator
	 */
	public static function register(Validator &$v)
	{
		$v::addRule('file', [static::class, 'validate'], 'is too big or has wrong format.');
		return $v;
	}

	/**
	 * Custom Valitron file attachments validation
	 *
	 * @param string $field Field to validate.
	 * @param File|null $value Field value.
	 * @param array $params Rule params
	 * @param array $fields All available fields.
	 *
	 * @return bool
	 */
	public static function validate($field, $value, array $params, array $fields)
	{
		$allowTypes = $params[0] ?? [];
		$allowSize = $params[1] ?? 2000000;

		if (empty($value) || ! is_a($value, File::class)) {
			return false;
		} elseif (0 < $allowSize && $value->size >= $allowSize) {
			return false;
		} elseif (!empty($allowTypes) && ! in_array($value->getExtension(), $allowTypes)) {
			return false;
		}

		return true;
	}
}
