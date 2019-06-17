<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;

/**
 * Part of program created by David Jungman
 *
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class UserManager
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * @var \App\Repository\UserRepository
     */
    private $userRepository;

    /**
     * @var \App\Repository\RoleRepository
     */
    private $roleRepository;

    /**
     * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var \Twig\Environment
     */
    private $templating;

    /**
     * UserManager constructor.
     *
     * @param  \Swift_Mailer  $mailer
     * @param  \Doctrine\ORM\EntityManagerInterface  $em
     * @param  \App\Repository\UserRepository  $userRepository
     * @param  \App\Repository\RoleRepository  $roleRepository
     * @param  \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface  $encoder
     * @param  \Twig\Environment  $templating
     */
    public function __construct(Swift_Mailer $mailer,
      EntityManagerInterface $em,
      UserRepository $userRepository,
      RoleRepository $roleRepository,
      UserPasswordEncoderInterface $encoder,
      Environment $templating)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->encoder = $encoder;
        $this->templating = $templating;
    }

    /**
     * Always use this method for creating new users
     *
     * Checks for duplicates with email address
     * Generates VerificationLink and sending email with MAILER_URL in .env file
     *
     *
     * @param $username
     * @param $password
     * @param $email
     * @param  null  $name
     * @param  null  $surname
     * @param  int  $role_id
     *
     * @return string
     */
    public function create($username, $password, $email, $name = null, $surname = null, $role_id = 1):string
    {
        $duplicate = $this->userRepository->findBy(["email" => $email]);
        if($duplicate) return "Email address must be unique!";

        $user = new User();
        $link = $user->generateVerificationLink();
        $user
            ->setUsername($username)
            ->setPassword($this->encoder->encodePassword($user, $password))
            ->setEmail($email)
            ->setName($name)
            ->setUsername($username)
            ->setRole($this->roleRepository->find($role_id));

        try
        {
            $this->em->persist($user);
            $this->sendVerification($email,$link);
            $this->em->flush();
        } catch(Exception $e)
        {
            return $e->getMessage();
        }

        return "User was created successfully";
    }

    /**
     * Sending Email about user creation and link for activating user
     *
     * @uses Emails/activation.html.twig as template
     *
     * @param  string  $emailTarget
     * @param  string  $verificationLink
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function sendVerification(string $emailTarget, string $verificationLink) {
        $message = (new Swift_Message("Potvrzení uživatelského účtu"))
            ->setFrom("neodpovidejte@hime.cz")
            ->setTo($emailTarget)
            ->setBody(
              $this->templating->render("Emails/activation.html.twig", array(
                'verification_link' => $verificationLink
              )), "text/html"
            );

        $this->mailer->send($message);
    }

    /**
     * Activates user if there is one with given link
     *
     * @param  string  $verificationLink
     *
     * @return bool
     */
    public function activateUser(string $verificationLink): bool
    {
        $user = $this->userRepository->findOneBy(["verificationLink" => $verificationLink]);
        if($user) $user->setActive(true);
        else return false;

        $this->em->merge($user);
        $this->em->flush();

        return true;
    }
}