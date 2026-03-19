<?php

namespace App\Form;

use Dom\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class MainSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, ['required' => false])
            ->add('type', ChoiceType::class, 
            [
                'required' => false,
                'placeholder' => 'global_search.all',
                'choices'  => SearchTypes::types(),
                'expanded' => true,
                'multiple' => false
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'translation_domain' => 'forms',
            'method' => Request::METHOD_GET,
            'csrf_protection' => false,
        ]);
    }
}
