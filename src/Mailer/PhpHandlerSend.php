<?php

namespace justcoded\form2email\Mailer;

use justcoded\form2email\DataObject\DataObject;
use justcoded\form2email\Message\Message;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PhpHandlerSend extends DataObject
{

    public function send($formFields, Message $message)
    {
        $mail = new PHPMailer(true);                    // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $this->getHost(); // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $this->getUser();                 // SMTP username
            $mail->Password = $this->getPassword();                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            if ($message->getFromAddress() != '' && $message->getFromName() != '') {
                $mail->setFrom($message->getFromAddress(), $message->getFromName());
            }
//            $mail->addAddress($this->message->getFrom());     // Add a recipient
//            $mail->addReplyTo('info@example.com', 'Information');

            if ($message->getCcAddress() != '' && $message->getBccName() != '') {
                $mail->addCC($message->getCcAddress(), $message->getBccName());
            }

            if ($message->getBccAddress() != '' && $message->getBccName() != '') {
                $mail->addBCC($message->getBccAddress(), $message->getBccName());
            }

            //Attachments
//            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $message->getSubject();
            $mail->Body = $message->getTemplate($formFields);
            $mail->AltBody = $message->getAltBody();

            $mail->send();

            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}