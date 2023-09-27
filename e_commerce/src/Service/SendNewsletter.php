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
        string $subject,
        string $template,
        array $context
    ):void
    {

        $recipients = [
            'ladylouis1971@gmail.com',
            'etrefouetsage@gmail.com'
        ];

        // $users = $em->;
        // $recipients = array_map(function ($user) {
        //     return new Address($user->getEmail());
            
        // }, $users);

        // On créer le mail
        $email = (new TemplatedEmail())
            ->from($from)
            ->to(...$recipients)
            ->subject($subject)
            ->htmlTemplate("email/$template.html.twig")
            ->context($context);

        // On envoie le mail
        $this->mailer->send($email);
    }
}