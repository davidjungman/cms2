<?php

namespace App\Controller\Admin\Components;


use App\Repository\ComponentRepository;
use App\Service\Breadcrumb;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/widgets")
 *
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class WidgetController extends BaseComponent
{
    const COMPONENT_NAME = "widget_manager";

    /**
     * @var \App\Service\Breadcrumb
     */
    private $breadcrumb;

    /**
     * ContentManager constructor.
     *
     * @param  \App\Repository\ComponentRepository  $repository
     * @param  \App\Service\Breadcrumb  $breadcrumb
     * @param  \App\Repository\WidgetRepository  $widgetRepository
     */
    public function __construct(ComponentRepository $repository, Breadcrumb $breadcrumb)
    {
        $breadcrumb = parent::__construct($repository,$breadcrumb);
        $this->breadcrumb = $breadcrumb->addItem("content.manager", "content_index");
    }

    /**
     * @Route("/new", name="widget_new")
     */
    public function new()
    {

    }

    /**
     * @Route("", name="widget_index")
     */
    public function index()
    {

    }
}