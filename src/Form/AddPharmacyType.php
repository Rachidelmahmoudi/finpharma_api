<?php

namespace App\Form;

use App\Entity\Pharmacy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPharmacyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'add_establishement.pharmacy.name',
                'row_attr' => [
                    'class' => 'form-group mb-3'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'add_establishement.pharmacy.name',
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'add_establishement.pharmacy.address',
                'row_attr' => [
                    'class' => 'form-group mb-3'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'add_establishement.pharmacy.address',
                ]
            ])
            ->add('phone', IntlPhoneType::class, ['label' => false])
            ->add('city', CityType::class, [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pharmacy::class,
            'translation_domain' => 'forms',
        ]);
    }
}
