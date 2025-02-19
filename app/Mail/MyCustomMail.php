<?php

namespace App\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MyCustomMail extends Mailable
{
    use SerializesModels;

    public function build()
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.example.com';                     // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'user@example.com';                     // SMTP username
            $mail->Password   = 'secret';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            // Recipients
            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress('recipient@example.com', 'Recipient');    // Add a recipient

            // Content
            $mail->isHTML(true);                                       // Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
