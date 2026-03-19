<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Validator\CheckAddDoctor;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddEstablishementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $options = [
            'add_establishement.types.doc' => 'doctor',
            'add_establishement.types.pharmacy' => 'pharmacy', 
            'add_establishement.types.laboratory' => 'laboratory',
            'add_establishement.others' => 'other'
        ];
        $options_with_attrs = function() use ($options) : array {
            foreach($options as $key => $option) {
                $options[$key] = [
                    'onchange' => 'this.closest(\'form\').submit();'
                ];
            }
            return $options;
        };
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'add_establishement.choose_your_type',
                'choices'  => $options,
                'choice_attr' => $options_with_attrs(),
                'expanded' => true,
                'multiple' => false,
                'label_attr' => [
                    'class' => 'mb-3 title'
                ]
            ])
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                [$this, 'onPreSubmit']
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
             'translation_domain' => 'forms',
        ]);
    }

    public function onPreSubmit(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        if (!$event->getForm()->has('business')) {
            $form = $event->getForm();
            $business_type = match($data['type'] ?? '') {
                'pharmacy' => [AddPharmacyType::class],
                'doctor' => [AddDoctorType::class],
                'laboratory' => [AddLaboratoryType::class],
                default => [OtherActvityType::class]
            };
            $business_type[] = ['label' => 'add_establishement.activity_informations', 'label_attr' => ['class' => 'mb-3 title'], 'constraints' => [new CheckAddDoctor]];
            $form->add('business', ...$business_type)
            ->add('save', SubmitType::class, [
                'label' => 'add_establishement.submit',
                'attr' => ['class' => 'btn_1'],
            ]);
        }
    }
}
