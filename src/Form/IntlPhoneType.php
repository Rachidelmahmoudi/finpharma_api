<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class IntlPhoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('phone', TextType::class, [
                'row_attr' => [
                    'class' => 'form-group mb-0'
                ],
                'label' => 'phone.label',
                'attr' => [
                    'class' => 'form-control phone',
                    'placeholder' => 'phone.placeholder',
                ],
                'label_attr' => [
                    'class' => 'd-block'
                ]
            ])
            ->add('full_phone', HiddenType::class, [
                'constraints' => [
                    new Assert\Length(min: 10)
                ]
            ])
            ->add('country_code', HiddenType::class)
        ;

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();
                $event->setData($data['full_phone'] ?? '');
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms',
        ]);
    }
}
