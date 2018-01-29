<?php

namespace JustCoded\FormHandler;

use JustCoded\FormHandler\Handlers\HandlerInterface;
use Valitron\Validator;
use JustCoded\FormHandler\Validator\File as FileValidator;

/**
 * Class FormHandler
 *
 * @package JustCoded\FormHandler
 */
class FormHandler
{
	/**
	 * Form validation rules
	 *
	 * @var array
	 */
	protected $rules;

	/**
	 * MailHandler
	 *
	 * @var HandlerInterface
	 */
	protected $handler;

	/**
	 * Response output format, by default 'json'
	 *
	 * @var string
	 */
	protected $response;

	/**
	 * List of errors
	 *
	 * @var array
	 */
	protected $errors = [];

	/**
	 * Handled form fiels
	 *
	 * @var array
	 */
	protected $formFields;

	/**
	 * FormHandler constructor.
	 *
	 * @param array $validationRules validation rules
	 * @param HandlerInterface $handler Mailer
	 * @param string $response Output format
	 */
	public function __construct(array $validationRules, HandlerInterface $handler, string $response = 'json')
	{
		$this->rules = $validationRules;

		$this->handler = $handler;

		$this->response = $response;
	}

	/**
	 * Validate form data
	 *
	 * @param array $data Array with Global variaable _POST
	 *
	 * @return bool
	 */
	public function validate(array $data)
	{
		$this->formFields = $data;
		$v                = new Validator($data);

		$v = FileValidator::validate($v, $this->rules);
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
	 * Method for returning a response
	 *
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
