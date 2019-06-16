<?php

namespace App\Form;


use App\Entity\Email;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used for sending email as part of Contact Email
 *
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class EmailType extends AbstractType
{

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name', TextType::class, array(
            'label' => 'label.name',
          ))
          ->add('clientEmail', \Symfony\Component\Form\Extension\Core\Type\EmailType::class, array(
            'label' => 'label.email'
          ))
          ->add('subject', TextType::class, array(
            'label' => 'label.subject'
          ))
          ->add('message', TextareaType::class, array(
            'label' => 'label.message'
          ));
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Email::class,
        ));
    }
}