<?php

namespace justcoded\form2email\Mailer;


use justcoded\form2email\Message\Message;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PhpHandlerSend
{
    protected $message;

    protected $host;

    protected $userName;

    protected $password;

    public function __construct(array $config, Message $message)
    {
        if (array_key_exists('host', $config)) {
            $this->host = $config['host'];
        }

        if (array_key_exists('user', $config)) {
            $this->userName = $config['user'];
        }

        if (array_key_exists('pass', $config)) {
            $this->password = $config['pass'];
        }

        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }


    public function send($formFields)
    {
        $mail = new PHPMailer(true);                    // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $this->getHost(); // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $this->getUserName();                 // SMTP username
            $mail->Password = $this->getPassword();                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            if ($this->message->getFromAddress() != '' && $this->message->getFromName() != '') {
                $mail->setFrom($this->message->getFromAddress(), $this->message->getFromName());
            }
//            $mail->addAddress($this->message->getFrom());     // Add a recipient
//            $mail->addReplyTo('info@example.com', 'Information');

            if ($this->message->getCcAddress() != '' && $this->message->getBccName() != '') {
                $mail->addCC($this->message->getCcAddress(), $this->message->getBccName());
            }

            if ($this->message->getBccAddress() != '' && $this->message->getBccName() != '') {
                $mail->addBCC($this->message->getBccAddress(), $this->message->getBccName());
            }

            //Attachments
//            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $this->message->getSubject();
            $mail->Body = $this->message->getTemplate($formFields);
            $mail->AltBody = $this->message->getAltBody();

            $mail->send();

            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}