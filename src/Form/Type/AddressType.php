<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AddressType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add('street', TextType::class, [
            'label' => 'adresse',
        ]);

        if ($options['with_country']) {
            $builder->add('country', ChoiceType::class, [
                'label' => 'pays',
                'Choices' => [
                    'France' => 'FR',
                    'Belgique' => 'be'
                ]
            ]);
        }
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'with_country' => true
        ]);
    }
}
