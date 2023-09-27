<?php
namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendNewsletter
{
    private $mailer;

    public function __construct(MailerInterface $mailer) {

        $this->mailer = $mailer;
    }

    public function send(
        string $from,
        string $to,
        string $cc,
        string $subject,
        string $template,
        array $context
    ):void
    {
        // On créer le mail
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->cc('user')
            ->subject($subject)
            ->htmlTemplate("email/$template.html.twig")
            ->context($context);

        // On envoie le mail
        $this->mailer->send($email);
    }
}