<?php

namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Swift_Message;

/**
 * Part of program created by David Jungman
 *
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class UserManager
{

    private $mailer;

    private $em;

    public function __construct(Swift_Mailer $mailer, EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->em = $em;
    }

    /**
     * @param  string  $emailTarget
     * @param  string  $verificationLink
     */
    public function sendVerification(string $emailTarget, string $verificationLink) {
        $message = (new Swift_Message("Potvrzení uživatelského účtu"))
            ->setFrom("neodpovidejte@hime.cz")
            ->setTo($emailTarget)
            ->setBody(
        "<a href='127.0.0.1/activation/link/$verificationLink'>Potvrzení účtu</a>",
    "text/html"
            );
        $this->mailer->send($message);
    }
}