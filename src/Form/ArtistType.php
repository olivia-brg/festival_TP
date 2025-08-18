<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('name', TextType::class, [
                'label' => 'Artist name',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter artist name'

                ]
            ])
            ->add('description')
            ->add('style', ChoiceType::class, [
                'attr' => [
                    'class' => 'style'
                ],
                'choices' => [
                    'Neurofunk' => 'Neurofunk',
                    'Liquid' => 'Liquid',
                    'Jungle' => 'Jungle',
                    'Ragga Jungle' => 'Ragga Jungle',
                    'Jump Up' => 'Jump Up',
                ]
            ])
            ->add('mixDate', DateType::class)
            ->add('mixTime', TimeType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Save'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
