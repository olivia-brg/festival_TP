<?php

namespace App\Form;

use App\Entity\artist;
use App\Entity\Music;
use App\Repository\ArtistRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter a title'
                ]
            ])
            ->add('releaseDate', DateType::class)
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
            ->add('artist', EntityType::class, [
                'placeholder' => 'Select an artist',
                'class' => Artist::class,
                'choice_label' => 'name',
                'query_builder' => function (ArtistRepository $ar) {
                return $ar->createQueryBuilder('a')
                    ->orderBy('a.name', 'ASC');
                }
            ])
            ->add('submit', SubmitType::class, [])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Music::class,
        ]);
    }
}
