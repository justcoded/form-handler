<?php

namespace justcoded\form2email;


use justcoded\form2email\Handler\HandlerInterface;
use justcoded\form2email\Message\Message;
use Valitron\Validator;

class FormHandler
{
    /**
     * @var array
     */
    protected $validation;

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
     * @var int
     */
    protected $status = 0;

    /**
     * @var array
     */
    protected $formFields;

    /**
     * FormHandler constructor.
     * @param array $validation
     * @param HandlerInterface $handler
     * @param string $response
     */
    public function __construct(array $validation, HandlerInterface $handler, string $response = 'json')
    {
        $this->validation = $validation;

        $this->handler = $handler;

        $this->response = $response;
    }

    /**
     * @param $post
     * @return bool
     */
    public function validate($post)
    {
        $this->formFields = $post;
        $v = new Validator($post);

        foreach ($this->validation['rules'] as $key => $rules) {
            $v->rule($key, $rules['fields'])->message($rules['message']);
        }

        $v->labels($this->validation['labels']);

        if ($v->validate()) {
            return true;
        } else {
            $this->status = 1;
            $this->errors = $v->errors();

            return false;
        }
    }

    /**
     * @param Message $message
     */
    public function process(Message $message)
    {
        $this->handler->process($this->formFields, $message);// sending email
    }

    /**
     * @return array
     */
    public function response()
    {
        return [
            'status' => $this->status,
            'errors' => $this->errors
        ];
    }
}
