<?php

namespace App\Form;

use App\Entity\Doctor;
use App\Entity\Specialty;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddDoctorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'add_establishement.doc.name',
                'row_attr' => [
                    'class' => 'form-group'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'add_establishement.doc.name',
                ],
            ])
            ->add('address', TextType::class, [
                'row_attr' => [
                    'class' => 'form-group'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'add_establishement.doc.address',
                ]
            ])
            ->add('phone', IntlPhoneType::class, [
                    'label' => false,
                    'help' => 'add_establishement.doc.phone_help'
            ])
            ->add('city', CityType::class, [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group'
                ]
            ])
            ->add('specialty', EntityType::class, [
                'class' => Specialty::class,
                'choice_label' => 'name',
                'label' => 'add_establishement.doc.specility',
                'placeholder' => 'add_establishement.doc.choose_your_speciality',
                'attr' => [
                    'class' => 'form-control select2'
                ],
                'help' => 'add_establishement.doc.speciality_help'
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'form-group'
                ],
                'attr' => [
                    'class' => 'form-control wysiwyg',
                    'placeholder' => 'add_establishement.doc.description',
                ],
                'help' => 'add_establishement.doc.description_help'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Doctor::class,
            'translation_domain' => 'forms',
        ]);
    }
}
