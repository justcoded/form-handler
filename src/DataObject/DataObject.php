<?php

namespace justcoded\form2email\DataObject;


class DataObject
{
    /**
     * @var array
     */
    public $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed|null
     */
    public function getMailerId()
    {
        if (array_key_exists('mailer', $this->config)) {
            return $this->config['mailer'];
        }

        return null;
    }

    /**
     * @return mixed|null
     */
    public function getHost()
    {
        if (array_key_exists('host', $this->config)) {
            return $this->config['host'];
        }

        return null;
    }

    /**
     * @return mixed|null
     */
    public function getUser()
    {
        if (array_key_exists('user', $this->config)) {
            return $this->config['user'];
        }

        return null;
    }

    /**
     * @return mixed|null
     */
    public function getPassword()
    {
        if (array_key_exists('password', $this->config)) {
            return $this->config['password'];
        }

        return null;
    }
}