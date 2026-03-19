<?php

namespace App\Form;

use App\Entity\Analyses;
use App\Entity\Laboratory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddLaboratoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'add_establishement.labo.name',
                'row_attr' => [
                    'class' => 'form-group'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'add_establishement.labo.name',
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'add_establishement.labo.address',
                'row_attr' => [
                    'class' => 'form-group'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'add_establishement.labo.address',
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'add_establishement.labo.phone',
                'row_attr' => [
                    'class' => 'form-group'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'add_establishement.labo.phone',
                ],
                'help' => 'add_establishement.labo.phone_help',
            ])
            ->add('city', CityType::class, [
                'label' => false,
                'mapped' => false,
                'row_attr' => [
                    'class' => 'form-group'
                ],
            ])
            ->add('analyses', EntityType::class, [
                'label' => 'add_establishement.labo.select_the_analyes',
                'class' => Analyses::class,
                'choice_label' => 'name',
                'mapped' => false,
                'multiple' => true,
                'placeholder' => 'add_establishement.labo.select_the_analyes',
                'attr' => [
                    'class' => 'form-control select2'
                ],
                'help' => 'add_establishement.labo.analyses_help',
            ])
            ->add('description', TextareaType::class, [
                'row_attr' => [
                    'class' => 'form-group'
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control wysiwyg',
                    'placeholder' => 'add_establishement.labo.description',
                ],
                'help' => 'add_establishement.labo.description_help',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Laboratory::class,
            'translation_domain' => 'forms',
        ]);
    }
}
