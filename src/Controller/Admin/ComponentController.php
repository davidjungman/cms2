<?php

namespace App\Controller\Admin;


use App\Entity\Component;
use App\Form\ComponentSwitchType;
use App\Form\ComponentType;
use App\Repository\ComponentRepository;
use App\Repository\SettingsRepository;
use App\Service\Breadcrumb;
use App\Service\BundleManager;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * ComponentController allows to manage Component Entities
 *  Order activation of Component
 *  Disable currently activated Component
 *  Dismiss order for activation
 *  Enable disabled Component without confirmation of Administrator
 *
 * @Route("/admin/components")
 *
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
final class ComponentController extends AbstractController
{

    /**
     * @var \App\Repository\ComponentRepository
     */
    private $componentRepository;

    public function __construct(ComponentRepository $componentRepository)
    {
        $this->componentRepository = $componentRepository;
    }

    /**
     * @Route("/{page<\d+>}", defaults={"page":1},name="component_index")
     *
     * @IsGranted("ROLE_ADMIN", message="This page is accessible only for administrators!")
     *
     * @param  int  $page
     * @param  \App\Service\Breadcrumb  $breadcrumb
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(int $page,Breadcrumb $breadcrumb)
    {
        $breadcrumb->addItem('nav.component.manager', "component_index");

        $components = $this->componentRepository->findAllByPage($page);

        $maxPages = ceil($components->count() / COMPONENT::ITEMS_PER_PAGE);

        $orderedComponents = $this->componentRepository->findBy(["isRequired" => true]);

        return $this->render('Admin/Component/index.html.twig', array(
            "breadcrumb" => $breadcrumb->createView(),
            "components" => $components,
            "orderedComponents" => $orderedComponents,
            "maxPages" => $maxPages,
            "thisPage" => $page
        ));
    }

    /**
     * Detail on Component Entity
     *
     * @Route("/view/{id<\d+>}", name="component_view")
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \App\Entity\Component  $component
     *
     * @param  \App\Service\Breadcrumb  $breadcrumb
     *
     * @param  \App\Service\BundleManager  $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view(Request $request, Component $component, Breadcrumb $breadcrumb, BundleManager $manager)
    {
        $form = $this->createForm(ComponentSwitchType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if(!$component->getIsStandaloneComponent())
            {
                $manager->activateComponentDependencies($component);
            }

            /**
            * If component was once activated, user can switch between DISABLED and ENABLED without REQUIRED step
            */
            $manager->activateSingleComponent($component);

            $em = $this->getDoctrine()->getManager();
            $em->merge($component);
            $em->flush();

            return $this->redirectToRoute("component_view", array("id" => $component->getId()));
        }

        $breadcrumb->addItem($component->getName(), "component_view");

        return $this->render("Admin/Component/view.html.twig", array(
          "component" => $component,
          "form" => $form->createView(),
          "bundle" => $component->getDependencies(),
          "bundlePrice" => $component->getTotalPrice(),
          "breadcrumb" => $breadcrumb->createView()
        ));
    }

    /**
     * @Route("/new", name="component_new")
     *
     * @IsGranted("ROLE_ADMIN", message="This page is accessible only for administrators!")
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \App\Repository\SettingsRepository  $settingsRepository
     * @param  \App\Service\Breadcrumb  $breadcrumb
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, SettingsRepository $settingsRepository, Breadcrumb $breadcrumb)
    {
        $breadcrumb->addItem("nav.component.manager", "component_index");
        $breadcrumb->addItem('new.component', "component_new");

        $component = new Component();
        $component
            ->setSettings($settingsRepository->find(1));

        $form = $this->createForm(ComponentType::class,$component)
          ->add('registerComponent', SubmitType::class, array('label' => 'register.component'))
          ->add('registerComponentAndNext', SubmitType::class, array('label' => 'register.component.and.next'))
          ->add('registerComponentAsStandalone', SubmitType::class, array('label' => 'register.component.as.standalone'));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if($form->get('registerComponentAsStandalone')->isClicked())
                $component->setIsStandaloneComponent(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($component);
            $em->flush();

            $this->addFlash('success', 'Komponenta byla vytvořena.');

            if($form->get('registerComponentAndNext')->isClicked())
            {
                return $this->redirectToRoute("component_new");
            } return $this->redirectToRoute('component_index');
        }


        return $this->render('Admin/Component/new.html.twig', array(
            "breadcrumb" => $breadcrumb->createView(),
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/update/{component}", name="component_update")
     *
     * @IsGranted("ROLE_ADMIN", message="This page is accessible only for administrators!")
     *
     * @param  \App\Entity\Component  $component
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @param  \App\Service\Breadcrumb  $breadcrumb
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Component $component, Request $request, Breadcrumb $breadcrumb)
    {
        $breadcrumb->addItem("nav.component.manager", "component_index");
        $breadcrumb->addItem("update.component", "component_update");

        $form = $this->createForm(ComponentType::class, $component)
          ->add('update', SubmitType::class, array("label" => "updateAndSave"))
          ->add('delete', SubmitType::class, array('label' => 'deleteComponent'));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            if($form->get("update")->isClicked())
            {
                $em->persist($component);
                $em->flush();

                $this->addFlash("success", "Komponenta byla úspěšně upravena");

            }
            else if($form->get("delete")->isClicked())
            {
                $em->remove($component);
                $em->flush();


                $this->addFlash("success", "Komponenta byla úspěšně odstraněna");
            }

            return $this->redirectToRoute("component_index");
        }

        return $this->render('Admin/Component/update.html.twig', array(
            "breadcrumb" => $breadcrumb->createView(),
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/enable/{component}", name="component_enable")
     *
     * @IsGranted("ROLE_ADMIN", message="This page is accessible only for administrators!")
     *
     * @param  \App\Entity\Component  $component
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function enable(Component $component)
    {
        // if admin
        $component->setEnabled(true);
        $component->setEnabledAt(new DateTime());
        $component->setIsRequired(false);

        $em = $this->getDoctrine()->getManager();
        $em->merge($component);
        $em->flush();

        $this->addFlash("success", "Žádost o komponentu byla úspěšně schválena");
        return $this->redirectToRoute("component_index");
    }
}