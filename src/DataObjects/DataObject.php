<?php

namespace JustCoded\FormHandler\DataObjects;

/**
 * Class DataObject
 *
 * @package JustCoded\FormHandler\DataObjects
 */
abstract class DataObject
{
	/**
	 * DataObject constructor.
	 *
	 * @param array $config Abstract array of user config
	 *
	 * @throws \Exception Exception.
	 */
	public function __construct(array $config)
	{
		foreach ($config as $key => $value) {
			if (property_exists($this, $key)) {
				$this->{$key} = $value;
			} else {
				throw new \Exception('Property "' . $key . '" doesn\'t exists in ' . get_class($this));
			}
		}
	}
}