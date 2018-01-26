<?php

namespace JustCoded\FormHandler\FileManager;

use JustCoded\FormHandler\DataObjects\DataObject;
use JustCoded\FormHandler\DataObjects\File;

class FileManager extends DataObject
{
    public static function prepareUpload(array $fields)
    {
        $files = [];

        foreach ($fields as $field) {
            $file = $_FILES[$field];

            if ($file['error'] == 0) {
                $files[] = new File($file);
                $_POST[$field] = $files;
            }
        }

        return $files;
    }
}