<?php

namespace JustCoded\FormHandler\DataObjects;

abstract class DataObject
{
	public function __construct(array $config)
	{
		foreach ($config as $key => $value) {
			if (property_exists($this, $key)) {
				$this->$key = $value;
			} else {
				throw new \Exception('Property "' . $key . '" doesn\'t exists in ' . get_class($this));
			}
		}
	}
}