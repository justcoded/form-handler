<?php

namespace JustCoded\FormHandler\FileManager;

use JustCoded\FormHandler\DataObjects\DataObject;
use JustCoded\FormHandler\DataObjects\File;

class FileManager extends DataObject
{
    /**
     * @var string
     */
    protected $uploadPath;

    /**
     * @var string
     */
    protected $uploadUrl;

    public function upload(array $fields)
    {
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }

        $files = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $_FILES)) {
                $fileField = $_FILES[$field];
                $file = new File($fileField);

                $name = preg_replace('/[^\00-\255]+/u', '', $file->name);
                $name = str_replace('"', '', trim($name));
                /** @var File $file */
                $path = realpath($this->uploadPath) . '/' . $name . $file->uniqName;

                if ($file->error == 0 && move_uploaded_file($file->tmp_name, $path)) {

                    $file->uploadUrl = $this->uploadUrl . '/' . $name . $file->uniqName;
                    $file->uploadPath = $path;
                    $_POST[$field] = $file;
                    $files[] = $file;
                }
            }
        }

        return $files;
    }
}