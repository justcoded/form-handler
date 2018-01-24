<?php

namespace JustCoded\FormHandler;


use JustCoded\FormHandler\Handlers\HandlerInterface;
use Valitron\Validator;

class FormHandler
{
	/**
	 * @var array
	 */
	protected $rules;

	/**
	 * @var HandlerInterface
	 */
	protected $handler;

	/**
	 * @var string
	 */
	protected $response;

	/**
	 * @var array
	 */
	protected $errors = [];

	/**
	 * @var array
	 */
	protected $formFields;

	/**
	 * FormHandler constructor.
	 *
	 * @param array            $validationRules
	 * @param HandlerInterface $handler
	 * @param string           $response
	 */
	public function __construct(array $validationRules, HandlerInterface $handler, string $response = 'json')
	{
		$this->rules = $validationRules;

		$this->handler = $handler;

		$this->response = $response;
	}

	/**
	 * @param $data
	 *
	 * @return bool
	 */
	public function validate($data)
	{
		$this->formFields = $data;
		$v                = new Validator($data);

		// create rules from input array.
		foreach ($this->rules['rules'] as $key => $params) {
			$rule = $v->rule($key, $params['fields']);
			if (!empty($params['message'])) {
				$rule->message($params['message']);
			}
		}

		// apply labels.
		if (!empty($this->rules)) {
			$v->labels($this->rules['labels']);
		}

		// clean errors if we run validate several times.
		$this->errors = array();
		// validate, set errors.
		if (! $v->validate()) {
			$this->errors = $v->errors();
		}

		return empty($this->errors);
	}

	public function process()
	{
		$this->handler->process($this->formFields);
		$this->errors = $this->handler->getErrors();
	}

	/**
	 * @return array
	 */
	public function response()
	{
		return [
			'status' => empty($this->errors),
			'errors' => $this->errors
		];
	}
}
