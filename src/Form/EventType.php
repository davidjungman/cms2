<?php

namespace App\Form;


use App\Entity\Event;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('title', TextType::class)
          ->add('description', TextareaType::class, array(
            "required" => false
          ))
          ->add('start', DateType::class, array(
            "widget" => "single_text",
            'html5' => false,
            'attr' => [
              'readonly' => true
            ]
          ))
          ->add('end', DateType::class, array(
            "widget" => "single_text",
            'html5' => false,
            'attr' => [
              'readonly' => true
            ]
          ))
          ->add('color', ColorType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => Event::class
        ));
    }
}