<?php

namespace JustCoded\FormHandler\DataObjects;


class File extends DataObject
{
    public $name;

    public $type;

    public $tmp_name;

    public $error;

    public $size;

    public $uniqName;

    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->uniqName = sha1(uniqid(mt_rand(), true)) . '.' . $this->getExtension();
    }

    public function getExtension()
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }
}