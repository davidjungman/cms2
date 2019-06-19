<?php

namespace App\Form;


use App\Entity\Component;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class ComponentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('average_price', TextType::class)
            ->add('componentName', TextType::class)
            ->add('dependencies', EntityType::class, array(
                'class' => Component::class,
                'query_builder' => function (EntityRepository $er)
                {
                    return $er->createQueryBuilder('c')
                      ->andWhere('c.isCoreComponent = false')
                      ->andWhere('c.isStandaloneComponent = false');
                },
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => Component::class
        ));
    }
}