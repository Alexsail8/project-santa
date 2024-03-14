<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailSenderService
{
    private $mailer;
    private $sendEmail;
    public function __construct(MailerInterface $mailer, string $sendEmail)
    {
        $this->mailer = $mailer;
        $this->sendEmail = $sendEmail;
    }

    public function sendEmail(string $emailTo, string $subject, string $message)
    {
        $email = (new Email())
            ->from($this->sendEmail)
            ->to($emailTo)
            ->subject($subject)
            ->text('<p>'.$message.'</p>');

        $this->mailer->send($email);
    }
}

