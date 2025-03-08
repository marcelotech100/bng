<?php

namespace bng\System;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendEmail
{
    public function send_email($subject, $body, $data)
    {
        $mail = new PHPMailer(true);

        try {
            // IMPORTANT
            // Uncomment to add server options for your email account provider server options
            /*
               $mail->SMTPDebug = SMTP::DEBUG_SERVER;
               $mail->isSMTP();
               $mail->Host = EMAIL_HOST;
               $mail->SMTPAuth = true;
               $mail->Username = EMAIL_USERNAME;
               $mail->Password = EMAIL_PASSWORD;
               $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
               $mail->Port = EMAIL_PORT;
             */

            $mail->setFrom(EMAIL_FROM);
            $mail->addAddress($data['to']);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body = $this->$body($data);

            $mail->send();

            return [
                'status' => 'success'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $mail->ErrorInfo
            ];
        }
    }

    private function email_body_new_agent($data)
    {
        $html = '<p>Para concluir o processo de registro de agente, clique no link abaixo: </p>';
        $html .= '<a href="' . $data['link'] . '">Concluir registro de agente.</a>';
        return $html;
    }

    private function codigo_recuperar_password($data)
    {
        $html = "<p>Para definir a sua password, use o seguinte código:</p>";
        $html .= "<h3>{$data['code']}</h3>";
        return $html;
    }
}
