<?php

namespace justcoded\form2email\Message;

class Message
{
    /**
     * @var array
     */
    protected $from;

    /**
     * @var array
     */
    protected $to;

    /**
     * @var array
     */
    protected $cc;

    /**
     * @var array
     */
    protected $bcc;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var string
     */
    protected $altBody;

    /**
     * Message constructor.
     * @param array $message
     */
    public function __construct(array $message)
    {
        foreach($message as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return mixed
     */
    public function getFromAddress()
    {
        return $this->from[0];
    }

    /**
     * @return mixed
     */
    public function getFromName()
    {
        return $this->from[1];
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return mixed
     */
    public function getToAddress()
    {
        return $this->to[0];
    }

    /**
     * @return mixed
     */
    public function getToName()
    {
        return $this->to[1];
    }

    /**
     * @return mixed
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @return mixed
     */
    public function getCcAddress()
    {
        return $this->cc[0];
    }

    /**
     * @return mixed
     */
    public function getCcName()
    {
        return $this->cc[1];
    }

    /**
     * @return mixed
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @return mixed
     */
    public function getBccAddress()
    {
        return $this->bcc[0];
    }

    /**
     * @return mixed
     */
    public function getBccName()
    {
        return $this->bcc[1];
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param array $formFields
     * @return mixed|string
     */
    public function getTemplate(array $formFields)
    {
        return template($this->body, $formFields);
    }

    /**
     * @return mixed
     */
    public function getAltBody()
    {
        return $this->altBody;
    }
}