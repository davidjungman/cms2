<?php

namespace App\Controller\Admin;


use App\Entity\Component;
use App\Form\SettingsType;
use App\Repository\ComponentRepository;
use App\Repository\SettingsRepository;
use App\Service\Breadcrumb;
use App\Util\BreadcrumbInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SettingsController allows to manage Settings Entity
 *  Redirects on Component Detail when clicked on row in table of Components
 *
 * @Route("/admin/settings")
 *
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
final class SettingsController extends AbstractController
{

    /**
     * @var \App\Repository\SettingsRepository
     */
    private $settingsRepository;

    /**
     * @var \App\Repository\ComponentRepository
     */
    private $componentRepository;

    /**
     * SettingsController constructor.
     *
     * @param  \App\Repository\SettingsRepository  $settingsRepository
     * @param  \App\Repository\ComponentRepository  $componentRepository
     */
    public function __construct(SettingsRepository $settingsRepository, ComponentRepository $componentRepository)
    {
        $this->settingsRepository = $settingsRepository;
        $this->componentRepository = $componentRepository;
    }

    /**
     * @Route("/{page<\d+>}", defaults={"page":1}, name="settings_index")
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  int  $page
     *
     * @param  \App\Service\Breadcrumb  $breadcrumb
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, int $page, Breadcrumb $breadcrumb)
    {
        $components = $this->componentRepository->findAllByPage($page);
        $settings = $this->settingsRepository->find(1);

        $totalComponents = $this->componentRepository->countAll();
        $totalEnabledComponents = $this->componentRepository->countEnabled();

        $maxPages = ceil($components->count() / Component::ITEMS_PER_PAGE);

        $form = $this->createForm(SettingsType::class, $settings)
          ->add("submit", SubmitType::class, array("label_format" => "submit.save_changes"));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->merge($settings);
            $em->flush();
            $this->addFlash("success", "system.update.successful");
            return $this->redirectToRoute("settings_index");
        }

        return $this->render("Admin/Settings/index.html.twig", array(
          "totalComponents" => $totalComponents,
          "totalEnabledComponents" => $totalEnabledComponents,
          "components" => $components,
          "form" => $form->createView(),
          "maxPages" => $maxPages,
          "thisPage" => $page,
          "breadcrumb" => $breadcrumb->addItem("Nastavení", "settings_index")->createView()
        ));
    }
}