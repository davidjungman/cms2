<?php

namespace App\Controller\Admin\Components;


use App\Entity\Email;
use App\Form\EmailType;
use App\Repository\ComponentRepository;
use App\Repository\EmailRepository;
use App\Service\Breadcrumb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Email Component allows managing Email Entities
 *  Reading incoming Emails
 *  Tagging them as Read
 *
 * @Route("/admin/emails")
 *
 * @uses Breadcrumb
 *
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
final class EmailController extends BaseComponent
{

    /**
     * Component name
     */
    const COMPONENT_NAME = "contact_form";

    /**
     * @var \App\Service\Breadcrumb
     */
    private $breadcrumb;

    /**
     * EmailController constructor.
     *
     * @param  \App\Repository\ComponentRepository  $repository
     * @param  \App\Service\Breadcrumb  $breadcrumb
     */
    public function __construct(ComponentRepository $repository, Breadcrumb $breadcrumb)
    {
        $this->breadcrumb = parent::__construct($repository, $breadcrumb);
    }

    /**
     * List of Email Entities
     *
     * @Route("/{page<\d+>}", defaults={"page": 1}, name="email_index")
     * @param  \App\Repository\EmailRepository  $repository
     * @param  int  $page
     *
     * @param  \App\Service\Breadcrumb  $breadcrumb
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(EmailRepository $repository, int $page, Breadcrumb $breadcrumb)
    {
        $emails = $repository->findAllByPage($page);

        $maxPages = ceil($emails->count() / Email::ITEMS_PER_PAGE);

        $unread_emails = $repository->countActiveEmails();

        $breadcrumb->addItem("Seznam emailů", "email_index");

        return $this->render("Admin/Component/Email/index.html.twig", array(
          "emails" => $emails,
          "unread_emails" => $unread_emails,
          "maxPages" => $maxPages,
          "thisPage" => $page,
          "breadcrumb" => $breadcrumb->createView()
        ));
    }

    /**
     * Detail of Email Entity
     *
     * @Route("/view/{id<\d+>}", name="email_view")
     *
     * @param  \App\Entity\Email  $email
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @param  \App\Service\Breadcrumb  $breadcrumb
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view(Email $email, Request $request, Breadcrumb $breadcrumb)
    {
        $form = $this->createForm(EmailType::class, $email)
          ->add('setAsRead', SubmitType::class, array("label" => "set as read"));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            if($form->get('setAsRead')->isClicked())
            {
                $email->setActive(0);
                $em = $this->getDoctrine()->getManager();
                $em->merge($email);
                $em->flush();
                $this->addFlash("warning", "alert.email_was_set_as_read");
                return $this->redirectToRoute("email_index");
            }
        }

        $breadcrumb->addItem("Detail emailu", "email_view");

        return $this->render("Admin/Component/Email/view.html.twig", array(
            "form" => $form->createView(),
            "email" => $email,
            "breadcrumb" => $breadcrumb->createView()
        ));
    }
}