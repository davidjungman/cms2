<?php

namespace App\Controller\Admin\Components;


use App\Entity\Page;
use App\Form\PageType;
use App\Repository\ComponentRepository;
use App\Repository\PageRepository;
use App\Repository\WidgetRepository;
use App\Service\Breadcrumb;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/content")
 *
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class ContentManager extends BaseComponent
{
    const COMPONENT_NAME = "content_manager";

    /**
     * @var \App\Service\Breadcrumb
     */
    private $breadcrumb;

    /**
     * @var \App\Repository\WidgetRepository
     */
    private $widgetRepository;

    /**
     * ContentManager constructor.
     *
     * @param  \App\Repository\ComponentRepository  $repository
     * @param  \App\Service\Breadcrumb  $breadcrumb
     * @param  \App\Repository\WidgetRepository  $widgetRepository
     */
    public function __construct(ComponentRepository $repository, Breadcrumb $breadcrumb, WidgetRepository $widgetRepository)
    {
        $this->widgetRepository = $widgetRepository;

        $breadcrumb = parent::__construct($repository,$breadcrumb);
        $this->breadcrumb = $breadcrumb->addItem("content.manager", "content_index");
    }

    /**
     * @Route("", name="content_index")
     */
    public function index()
    {

    }

    /**
     * @Route("/text/{selectedPage}",defaults={"selectedPage":1}, name="content_text")
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \App\Entity\Page  $selectedPage
     * @param  \App\Repository\PageRepository  $pageRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function text(Request $request, Page $selectedPage, PageRepository $pageRepository)
    {
        $breadcrumb = $this->breadcrumb->addItem("text.manager", "content_text");

        $pages = $pageRepository->findAll();

        $original = clone $selectedPage;

        $form = $this->createForm(PageType::class, $selectedPage)
          ->add("submit", SubmitType::class, array("label" => "submit.save_changes"));

        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $selectedPage->setTitle($original->getTitle());
            if($selectedPage->getTitle() == null) $selectedPage->setTitle($original->getTitle());
            if($selectedPage->getContent() == null) $selectedPage->setContent($original->getContent());

            $em = $this->getDoctrine()->getManager();
            $em->merge($selectedPage);
            $em->flush();

            return $this->redirectToRoute("content_text", array("selectedPage" => $selectedPage->getId()));
        }

        return $this->render("Admin/Component/Content/text.html.twig", array(
          "breadcrumb" => $breadcrumb->createView(),
          "pages" => $pages,
          "selectedPage" => $selectedPage,
          "widgets" => $this->widgetRepository->findBy(["disabled" => false]),
          "form" => $form->createView()
        ));
    }

    /**
     * @Route("/photo", name="content_photo")
     */
    public function photo()
    {
        $breadcrumb = $this->breadcrumb->addItem("photo.manager", "content_photo");

        return $this->render("Admin/Component/Content/photo.html.twig", array(
          "breadcrumb" => $breadcrumb->createView()
        ));
    }

    /**
     * @Route("/widget", name="content_widget")
     * @param  \App\Repository\WidgetRepository  $widgetRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function widget()
    {
        $breadcrumb = $this->breadcrumb->addItem("nav.widget_manager", "content_widget");

        $widgets = $this->widgetRepository->findAll();

        return $this->render("Admin/Component/Content/widget.html.twig", array(
          "breadcrumb" => $breadcrumb->createView(),
          "widgets" => $widgets
        ));
    }
}