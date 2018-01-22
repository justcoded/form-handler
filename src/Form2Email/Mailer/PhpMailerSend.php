<?php

namespace justcoded\form2email\Mailer;

use justcoded\form2email\App\App;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PhpMailerSend implements MailerInterface
{

    public function process()
    {
        $mail = new PHPMailer(true);                    // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = App::getMailerHost(); // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = App::getMailerUser();                 // SMTP username
            $mail->Password = '';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('kostant21@yahoo.com', 'Mailer');
            $mail->addAddress('kostant21@yahoo.com', 'Joe User');     // Add a recipient
            $mail->addReplyTo('info@example.com', 'Information');
            $mail->addCC('cc@example.com');
            $mail->addBCC('bcc@example.com');

            //Attachments
//            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            $template = template(__DIR__. $this->config->getMessageTemplate(), [
                'name' => 'Hello World!'
            ]);

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $this->config->getMessageSubject();
            $mail->Body = $template;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();

            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}