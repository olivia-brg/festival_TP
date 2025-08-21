<?php

namespace App\Form;

use App\Entity\artist;
use App\Entity\Music;
use App\Entity\MusicGenre;
use App\Repository\ArtistRepository;
use App\Repository\MusicGenreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('artists', EntityType::class, [
                'label' => 'Artists',
                'attr' => [
                    'class' => 'style'
                ],
                'placeholder' => 'Select an artist',
                'class' => Artist::class,
                'choice_label' => 'name',
                'multiple' => true,
                'by_reference' => false,
                'query_builder' => function (ArtistRepository $ar) {
                    return $ar->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                }
            ])
            ->add('submit', SubmitType::class, []);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Music::class,
        ]);
    }
}
