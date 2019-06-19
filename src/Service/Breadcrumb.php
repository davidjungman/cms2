<?php

namespace App\Service;


use Symfony\Contracts\Translation\TranslatorInterface;

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

    private $translator;

    /**
     * Breadcrumb constructor.
     *
     * @param  \Symfony\Contracts\Translation\TranslatorInterface  $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;

        $this->separator = "/";
        $this->addItem("home", "landing_index");
        $this->addItem("admin", "dashboard_index");
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
          "value" => $this->translator->trans($name)
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