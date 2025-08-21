<?php

namespace App\Controller;

use App\Entity\Music;
use App\Form\MusicType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MusicController extends AbstractController
{
    #[Route('/music/add', name: 'music_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $music = new Music();
        $form = $this->createForm(MusicType::class, $music);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($music);
            $em->flush();

            $this->addFlash('success', 'Music added successfully');
            $firstArtist = $music->getArtists()->first();
            if ($firstArtist) {
                return $this->redirectToRoute('artist_id', ['id' => $firstArtist->getId()]);
            }

            return $this->redirectToRoute('artist_list');

//            return $this->redirectToRoute('artist_id', ['id' => $music->getArtists()->()]);
        }

        return $this->render('music/edit.html.twig', [
            'music_form' => $form,
            'mode' => 'add'
        ]);
    }
}
