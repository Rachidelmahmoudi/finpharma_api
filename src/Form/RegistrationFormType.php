<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'row_attr' => [
                    'class' => 'form-group'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'registration.firstname',
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('lastName', TextType::class, [
                'row_attr' => [
                    'class' => 'form-group'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'registration.lastname',
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('email', EmailType::class, [
                'row_attr' => [
                    'class' => 'form-group'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'registration.email',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email()
                ]
            ])
            ->add('phone', IntlPhoneType::class, [
                'mapped' => false, 
                'label' => false,
                'row_attr' => [
                    'class' => 'form-group mb-0'
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'registration.password', 
                    'hash_property_path' => 'password',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ],
                'second_options' => [
                    'label' => 'registration.confirm_password',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ],
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('terms', CheckboxType::class, [
                'label' => 'registration.terms_and_conditions',
                'mapped' => false,
                'label_attr' => [
                    'class' => 'd-block'
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
             'translation_domain' => 'forms',
        ]);
    }
}
