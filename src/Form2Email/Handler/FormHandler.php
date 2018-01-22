<?php

namespace justcoded\form2email\Handler;

use justcoded\form2email\App\App;
use justcoded\form2email\AppConfig\Settings;
use Valitron\Validator;

class FormHandler
{
    protected $validation;

    protected $handler;

    protected $response;

    protected $errors = [];

    protected $status = 0;

    protected $formFields = [];

    public function __construct(MailHandler $handler, $response = 'json')
    {
        $this->validation = Settings::$validation;

        $this->handler = $handler;

        $this->response = $response;
    }

    public function validate($post)
    {
        $v = new Validator($post);
        $v->mapFieldsRules($this->validation['rules']);

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
        $this->handler->process();// sending email
    }

    public function response()
    {
        return [
            'status' => $this->status,
            'errors' => $this->errors
        ];
    }
}
