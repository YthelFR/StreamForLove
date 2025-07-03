<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(string $recipient, string $subject, string $message, string $senderName): void
    {
        $signature = "<br><br>--<br>$senderName<br><img src='https://streamforlove.coalitionplus.org/assets/medias/headers/logo.png' alt='Logo' style='width: 150px;'>";

        $email = (new Email())
            ->from('support@streamforlove.coalitionplus.org')
            ->to($recipient)
            ->subject($subject)
            ->html($message . $signature); 

        $this->mailer->send($email);
    }
}
