<?php

namespace App\Controller\Admin\Components;


use App\Repository\ComponentRepository;
use App\Service\Breadcrumb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Default Component
 *  Provides ability to check if Component is activated
 *
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class BaseComponent extends AbstractController
{

    /**
     * BaseComponent constructor.
     *
     * @param  \App\Repository\ComponentRepository  $repository
     * @param  \App\Service\Breadcrumb  $breadcrumb
     */
    public function __construct(ComponentRepository $repository, Breadcrumb $breadcrumb)
    {
        $thisComponent = $repository->findOneBy(["componentName" => static::COMPONENT_NAME]);
        if($thisComponent == null || !$thisComponent->isEnabled())
        {
            throw new NotFoundHttpException("Page not found");
        }
        return $breadcrumb->addItem("component", "settings_index");
    }
}