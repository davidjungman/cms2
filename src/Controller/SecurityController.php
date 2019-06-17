<?php

namespace App\Controller;

use App\Service\UserManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @Route("/login", name="security_login")
     * @param  \Symfony\Component\Security\Http\Authentication\AuthenticationUtils  $authenticationUtils
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Security/login.html.twig', [
          'last_username' => $lastUsername,
          'error' => $error
        ]);
    }

    /**
     * @Route("/user/activation/link/{link}", name="user_activation")
     * @param  string  $link
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function activation(string $link)
    {
        $result = $this->userManager->activateUser($link);
        if(!$result)
        {
            throw new Exception("User not found!");
        } else
        {
            return $this->redirectToRoute("security_login");
        }
    }
}
