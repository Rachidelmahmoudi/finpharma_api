<?php

namespace App\Form;

use App\Service\CityLoader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityType extends AbstractType
{
    public function __construct(private readonly CityLoader $city_loader)
    {
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $cities = $this->city_loader->getCities();
        $choices = [];
        foreach ($cities as $city) {
            $choices[$city['city']] = $city['city'];
        }
        $builder
            ->add('city', ChoiceType::class, [
                'choices' => $choices,
                'row_attr' => [
                    'class' => 'form-group mb-0'
                ],
                'required' => false,
                'placeholder' => 'add_establishement.city.choose_your_city',
                'label' => 'add_establishement.doc.city',
                'attr' => [
                    'class' => 'form-control select2'
                ]
            ])
        ;

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();
                $event->setData($data['city'] ?? '');
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms'
        ]);
    }
}
