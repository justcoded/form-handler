<?php

namespace justcoded\form2email;


use justcoded\form2email\Handler\HandlerInterface;
use Valitron\Validator;

class FormHandler
{
    protected $validation;

    protected $handler;

    protected $response;

    protected $errors = [];

    protected $status = 0;

    protected $formFields;

    public function __construct(array $validation, HandlerInterface $handler, string $response = 'json')
    {
        $this->validation = $validation;

        $this->handler = $handler;

        $this->response = $response;
    }

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

    public function process()
    {
        $this->handler->process($this->formFields);// sending email
    }

    public function response()
    {
        return [
            'status' => $this->status,
            'errors' => $this->errors
        ];
    }
}
