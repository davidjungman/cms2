<?php

namespace App\Controller\Admin;


use App\Entity\Component;
use App\Form\ComponentSwitchType;
use App\Service\Breadcrumb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * Detail on Component Entity
     *
     * @Route("/view/{id<\d+>}", name="component_view")
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \App\Entity\Component  $component
     *
     * @param  \App\Service\Breadcrumb  $breadcrumb
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view(Request $request, Component $component, Breadcrumb $breadcrumb)
    {
        $form = $this->createForm(ComponentSwitchType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            /**
             * If component was once activated, user can switch between DISABLED and ENABLED without REQUIRED step
             */
            if($component->isDisabled()) {

                if($component->canBeEnabled())
                {
                    $component->enableComponent();
                } else
                {
                    $component->requireComponent();
                }
            }

            elseif($component->isEnabled()) $component->disableComponent();

            elseif($component->isRequired()) $component->setIsRequired(false);

            $em = $this->getDoctrine()->getManager();
            $em->merge($component);
            $em->flush();

            return $this->redirectToRoute("component_view", array("id" => $component->getId()));
        }

        return $this->render("Admin/Component/view.html.twig", array(
          "component" => $component,
          "form" => $form->createView(),
          "breadcrumb" => $breadcrumb->addItem($component->getName(), "component_view")->createView()
        ));
    }
}