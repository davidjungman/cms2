<?php

namespace App\Twig;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

/**
 * Part of program created by David Jungman
 *
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class TwigGlobalSubscriber implements EventSubscriberInterface
{
    private $twig;

    private $em;

    public function __construct(Environment $twig, EntityManagerInterface $em)
    {
        $this->twig = $twig;
        $this->em = $em;
    }

    public function injectGlobalVariables()
    {
        $enabledComponents = $this->em->getRepository('App\Entity\Component')->findBy(["enabled" => true]);
        $this->twig->addGlobal("enabledComponents", $enabledComponents);
    }

    public static function getSubscribedEvents()
    {
        return [
          KernelEvents::CONTROLLER => 'injectGlobalVariables',
        ];
    }
}