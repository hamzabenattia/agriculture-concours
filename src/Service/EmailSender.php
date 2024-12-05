<?php


namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailSender
{

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    public function sendEmail($sender,$recipient ,$subject, $html, $context)
    {
        $email = (new TemplatedEmail())
                ->from($sender)
                ->to($recipient)
                ->subject($subject)

                // path of the Twig template to render
                ->htmlTemplate($html)

                // change locale used in the template, e.g. to match user's locale
                ->locale('fr')

                // pass variables (name => value) to the template
                ->context($context);

            try {
                $this->mailer->send($email);

            } catch (TransportExceptionInterface $e) {
                // show error message
                
            }
    }
}