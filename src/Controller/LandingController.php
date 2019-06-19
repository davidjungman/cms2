<?php

namespace App\Controller;


use App\Entity\Email;
use App\Form\EmailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class LandingController extends AbstractController
{

    /**
     * @Route("/", methods={"GET", "POST"}, name="landing_index")
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $email = new Email();
        $email->setActive(1);

        $form = $this->createForm(EmailType::class, $email);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($email);
            $em->flush();

            $this->addFlash('success', 'post.send_successfully');
            return $this->redirectToRoute("landing_page");
        }

        return $this->render("base.html.twig", array(
          "form" => $form->createView()
        ));
    }
}