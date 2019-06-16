<?php

namespace App\Service;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class Breadcrumb
{

    /**
     * @var array
     */
    private $items = [];

    /**
     * @var string
     */
    private $separator;

    /**
     * Breadcrumb constructor.
     */
    public function __construct()
    {
        $this->separator = "/";
        $this->addItem("Domů", "landing_page");
        $this->addItem("Admin", "dashboard_index");
    }

    /**
     * Adds item into breadcrumb
     *
     * @param  string  $name
     * @param  string  $route
     *
     * @return \App\Service\Breadcrumb
     */
    public function addItem(string $name, string $route):self
    {
        array_push($this->items,array(
          "path" => $route,
          "value" => $name
        ));
        return $this;
    }

    /**
     * Changes separator
     *
     * @param  string  $separator
     *
     * @return \App\Service\Breadcrumb
     */
    public function setSeparator(string $separator):self
    {
        $this->separator = $separator;
        return $this;
    }

    /**
     * @return array
     */
    public function createView():array
    {
        return $this->items;
    }
}