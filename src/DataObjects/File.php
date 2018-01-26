<?php

namespace JustCoded\FormHandler\DataObjects;


class File extends DataObject
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $tmp_name;

    /**
     * @var int
     */
    public $error;

    /**
     * @var int
     */
    public $size;

    /**
     * @var string
     */
    public $uniqName;

    /**
     * @var string
     */
    public $uploadUrl;

    /**
     * @var string
     */
    public $uploadPath;

    /**
     * File constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->uniqName = sha1(uniqid(mt_rand(), true)) . '.' . $this->getExtension();
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    public function __toString()
    {
        return $this->uploadUrl;
    }
}