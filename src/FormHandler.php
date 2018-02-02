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
	const RESPONSE_JSON  = 'json';
	const RESPONSE_ARRAY = 'array';

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
	protected $response_format;

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
	 * @param array            $validationRules Validation rules.
	 * @param HandlerInterface $handler Valid data processor.
	 * @param string           $response Output format.
	 *
	 * @throws \Exception Validation rules are empty.
	 */
	public function __construct(array $validationRules, HandlerInterface $handler, string $response = 'json')
	{
		$this->rules = $validationRules;
		if (empty($this->rules['fields'])) {
			throw new \Exception("You should specify 'fields' in validation array.");
		}

		$this->handler = $handler;

		$this->response_format = $response;
	}

	/**
	 * Validate form data
	 *
	 * @param array $data Usually data from $_POST or $_REQUEST.
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
		$this->errors = array_filter(array($this->handler->getErrors()));
	}

	/**
	 * Method for returning a response
	 *
	 * @return array
	 */
	public function response()
	{
		return $this->formatResponse([
			'status' => empty($this->errors),
			'errors' => $this->errors
		]);
	}

	/**
	 * Format response according to response format.
	 *
	 * @param array $data Data to format.
	 *
	 * @return array|string Formatted data.
	 */
	public function formatResponse($data)
	{
		$response = null;
		switch ($this->response_format) {
			case static::RESPONSE_JSON:
				$response = json_encode($data);
				break;
			default:
				$response = $data;
		}
		return $response;
	}
}
