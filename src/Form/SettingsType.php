<?php

namespace App\Form;


use App\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("companyName", TextType::class)
            ->add("identificationNumber", TextType::class)
            ->add("dataBox", TextType::class, array("required" => false))

            ->add("contactTelephone", TelType::class)
            ->add("contactEmail", \Symfony\Component\Form\Extension\Core\Type\EmailType::class)

            ->add("addressCity", TextType::class)
            ->add("addressStreet", TextType::class)
            ->add("addressHouseNumber", TextType::class, array("required" => false))
            ->add("zipCode", TextType::class)
            ->add("addressState", CountryType::class, array(
              "preferred_choices" => array("CZ", "SK")
            ))

            ->add("version", TextType::class, array(
              "disabled" => true,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Settings::class
        ));
    }
}