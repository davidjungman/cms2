<?php

namespace App\Service;


use App\Entity\Component;

/**
 * Takes care of dependencies of Components
 *
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class BundleManager
{

    public function activateComponentDependencies(Component $component): void
    {
        /**
         * if component is standalone, it can't have any dependencies
         */
        if($component->getIsStandaloneComponent()) return;

        foreach($component->getDependencies() as $dependency)
        {
            $this->activateSingleComponent($dependency);
        }
    }

    public function activateSingleComponent(Component $component)
    {
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
    }
}