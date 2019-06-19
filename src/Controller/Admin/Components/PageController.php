<?php

namespace App\Controller\Admin\Components;


use App\Entity\Page;
use App\Form\PageType;
use App\Repository\ComponentRepository;
use App\Repository\PageRepository;
use App\Service\Breadcrumb;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @Route("/admin/page")
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class PageController extends BaseComponent
{
    const COMPONENT_NAME = "page_manager";

    /**
     * @var \App\Service\Breadcrumb
     */
    private $breadcrumb;

    /**
     * ContentManager constructor.
     *
     * @param  \App\Repository\ComponentRepository  $repository
     * @param  \App\Service\Breadcrumb  $breadcrum
     */
    public function __construct(ComponentRepository $repository, Breadcrumb $breadcrumb)
    {
        $breadcrumb = parent::__construct($repository,$breadcrumb);
        $this->breadcrumb = $breadcrumb->addItem("page.manager", "page_index");
    }

    /**
     * @Route("", name="page_index")
     * @param  \App\Repository\PageRepository  $repository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(PageRepository $repository)
    {
        $breadcrumb = $this->breadcrumb->addItem("page_list", "page_index");

        $pages = $repository->findAll();

        return $this->render("Admin/Component/Page/index.html.twig", array(
          "breadcrumb" => $breadcrumb->createView(),
          "pages" => $pages
        ));
    }

    /**
     * @Route("/create", name="page_new")
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @param  \App\Service\Breadcrumb  $breadcrumb
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, Breadcrumb $breadcrumb)
    {
        $breadcrumb->addItem("page.create", "page_new");

        $page = new Page();
        $form = $this->createForm(PageType::class, $page)
          ->add('submit', SubmitType::class, array("label" => "createNewPage"));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            $this->addFlash("success", "Stránka byl úspěšně přidána");
            return $this->redirectToRoute("page_index");
        }

        return $this->render('Admin/Component/Page/new.html.twig', array(
          "breadcrumb" => $breadcrumb->createView(),
          "form" => $form->createView()
        ));
    }

    /**
     * @Route("/update/{page}", name="page_update")
     *
     * @param  \App\Entity\Page  $page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function update(Page $page):RedirectResponse
    {
        return $this->redirectToRoute("content_text", array("selectedPage" => $page->getId()));
    }
}