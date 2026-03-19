<?php
namespace App\MessageHandler;

use App\Message\EmailNotification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class EmailNotificationHandler
{
    public function __construct(private readonly MailerInterface $mailer)
    {
        
    }
    public function __invoke(EmailNotification $message)
    {
        try {
            $email = (new Email())
            ->from($message->getFrom())
            ->to($message->getTo())
            ->subject($message->getSubject())
            ->html($message->getContent());
            $this->mailer->send($email);
        }
        catch(\Exception $ex) {
            
        }
    }
}