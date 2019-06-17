<?php

namespace App\Form;


use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("email", \Symfony\Component\Form\Extension\Core\Type\EmailType::class, array(
              "disabled" => true
            ))
            ->add("username", TextType::class, array(
              "disabled" => true
            ))
            ->add("name", TextType::class)
            ->add("surname", TextType::class)

            ->add("role", EntityType::class, [
              'class' => Role::class,
              'choice_label' => 'alias'
            ]);
    }
}