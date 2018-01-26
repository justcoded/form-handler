<?php

namespace JustCoded\FormHandler\Validator;

use Valitron\Validator;
use JustCoded\FormHandler\DataObjects\File as FileValidator;

class File
{
    public static function validate(Validator $v, $rules)
    {
        $allowSize = $rules['rules']['file']['allowSize'];
        $allowType = $rules['rules']['file']['allowType'];

        $v::addRule('file', function($field, $value, array $params, array $fields) use ($allowSize, $allowType) {
            /**
             * @var FileValidator $value
             */
            if ($value->size >= $allowSize) {
                return false;
            } elseif (! in_array($value->getExtension(), $allowType)) {
                return false;
            }

            return true;
        }, 'File error. ' . 'Max file size: ' . $allowSize . ' bytes. Allowable extensions: ' . implode(",", $allowType));

        return $v;
    }
}