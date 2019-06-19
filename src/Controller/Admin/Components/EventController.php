<?php

namespace App\Controller\Admin\Components;


use App\Entity\Event;
use App\Form\EventType;
use App\Repository\ComponentRepository;
use App\Repository\EventRepository;
use App\Service\Breadcrumb;
use App\Service\DateService;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/events")
 *
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
final class EventController extends BaseComponent
{
    const COMPONENT_NAME = "event_calendar";

    /**
     * @var \App\Service\Breadcrumb
     */
    private $breadcrumb;

    /**
     * @var \App\Repository\EventRepository
     */
    private $repository;

    public function __construct(ComponentRepository $componentRepository, Breadcrumb $breadcrumb, EventRepository $repository)
    {
        $this->repository = $repository;
        $this->breadcrumb = parent::__construct($componentRepository, $breadcrumb);
    }

    /**
     * Shows Calendar with Event Entities
     *
     * @Route("",name="event_index")
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @param  \App\Service\DateService  $dateService
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, DateService $dateService)
    {
        $this->breadcrumb->addItem("event.calendar", "event_index");

        $event = new Event();
        $event->setActive(1);

        $form = $this->createForm(EventType::class, $event)
          ->add('saveAndPublish', SubmitType::class, array("label" => "saveAndPublish"));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            if($dateService->compareDateTimes($event->getStart(), $event->getEnd()))
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->flush();

                $this->addFlash("success", "Událost byla vytvořena.");
                return $this->redirectToRoute("event_index");
            } else
            {
                $this->addFlash("danger", "Počátek události musí být dříve, než její konec!");
                return $this->redirectToRoute("event_index");
            }
        }

        return $this->render("Admin/Component/Event/index.html.twig", array(
            "breadcrumb" => $this->breadcrumb->createView(),
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/list", name="event_list")
     */
    public function list(): JsonResponse
    {
        $active_events = $this->repository->findBy(["active" => true]);
        return $this->json($active_events);
    }
}