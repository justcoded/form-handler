<?php

namespace justcoded\form2email\Message;

class Message
{
    protected $from;

    protected $to;

    protected $cc;

    protected $bcc;

    protected $subject;

    protected $body;

    protected $altBody;

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

    public function getFromAddress()
    {
        return $this->from[0];
    }

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

    public function getToAddress()
    {
        return $this->to[0];
    }

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

    public function getCcAddress()
    {
        return $this->cc[0];
    }

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

    public function getBccAddress()
    {
        return $this->bcc[0];
    }

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
     * @return mixed
     */
    public function getTemplate($formFields)
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