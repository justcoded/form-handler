<?php

namespace JustCoded\FormHandler;

use JustCoded\FormHandler\Handlers\HandlerInterface;
use Valitron\Validator;
use JustCoded\FormHandler\Validator\FileValidator;

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
		if (empty($this->rules['fields'])) {
			throw new \Exception("You should specify 'fields' in validation array.");
		}

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
		$v = $this->getValidator($data);

		$v->mapFieldsRules($this->rules['fields']);

		// apply labels.
		if (!empty($this->rules['labels'])) {
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

	/**
	 * Create validator object with registered custom validators.
	 *
	 * @param array $data Data to validate.
	 *
	 * @return Validator
	 */
	public function getValidator($data)
	{
		$v = new Validator($data);

		// register additional validators.
		FileValidator::register($v);

		return $v;
	}

	/**
	 * Sending email by handler
	 */
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
