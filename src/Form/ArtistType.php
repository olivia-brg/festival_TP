<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\MusicGenre;
use App\Repository\MusicGenreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('description', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter a description'
                ]
            ])
            ->add('mixDate', DateType::class)
            ->add('mixTime', TimeType::class)
            ->add('musicGenres', EntityType::class, [
                'label' => 'Style',
                'attr' => [
                    'class' => 'style'
                ],
                'placeholder' => 'Select a style',
                'class' => MusicGenre::class,
                'choice_label' => 'genre',
                'multiple' => true,
                'by_reference' => false,
                'query_builder' => function (MusicGenreRepository $ar) {
                    return $ar->createQueryBuilder('mg')
                        ->orderBy('mg.genre', 'ASC');
                }
            ])
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
