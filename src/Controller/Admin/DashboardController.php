<?php

namespace App\Controller\Admin;

use App\Service\Breadcrumb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * DashboardController shows core information
 *  Not used yet
 *
 * @Route("/admin")
 *
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
final class DashboardController extends AbstractController
{

    /**
     * Dashboard index
     *
     * @Route("", name="dashboard_index")
     * @param  \App\Service\Breadcrumb  $breadcrumb
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Breadcrumb $breadcrumb): Response
    {
        $breadcrumb->addItem("nav.dashboard", "dashboard_index");
        return $this->render("Admin/Dashboard/index.html.twig", array(
          "breadcrumb" => $breadcrumb->createView()
        ));
    }
}