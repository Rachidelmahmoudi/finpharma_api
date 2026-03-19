<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OtherActvityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('custom_type', TextType::class, [
                'label' => 'add_establishement.other.type',
                'row_attr' => [
                    'class' => 'form-group mb-3'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'add_establishement.other.type_placeholder',
                ],
                'help' => 'add_establishement.other.type_help'
            ])
           ->add('name', TextType::class, [
                'label' => 'add_establishement.other.name',
                'row_attr' => [
                    'class' => 'form-group mb-3'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'add_establishement.other.name',
                ],
                'help' => 'add_establishement.other.name_help'
            ])
            ->add('address', TextType::class, [
                'label' => 'add_establishement.other.address',
                'row_attr' => [
                    'class' => 'form-group mb-3'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'add_establishement.other.address_placeholer',
                ],
                'help' => 'add_establishement.other.address_help'
            ])
            ->add('phone', IntlPhoneType::class, [
                    'label' => false,
                   'help' => 'add_establishement.other.phone_help'
            ])
            ->add('city', CityType::class, [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group mb-3'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'add_establishement.other.description',
                'required' => false,
                'row_attr' => [
                    'class' => 'form-group mb-3'
                ],
                'attr' => [
                    'class' => 'form-control wysiwyg',
                    'placeholder' => 'add_establishement.other.description',
                ],
                'help' => 'add_establishement.other.description_help'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms',
        ]);
    }
}
