<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class ComponentSwitchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("switch", CheckboxType::class, array("required" => false));
    }
}