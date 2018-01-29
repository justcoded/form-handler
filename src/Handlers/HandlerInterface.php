<?php

namespace JustCoded\FormHandler\Handlers;

interface HandlerInterface
{
	/**
	 * Form process
	 *
	 * @param array $formFields Form fields
	 *
	 * @return mixed
	 */
	public function process(array $formFields);

	/**
	 * Getting errors
	 *
	 * @return mixed
	 */
	public function getErrors();
}
