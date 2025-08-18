<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/artist', name: 'artist_')]
final class ArtistController extends AbstractController
{

    #[Route('/page/{page}', name: 'list', requirements: ['page' => '\d+',], methods: ['GET'])]
    public function list(ArtistRepository $artistRepository, int $page, ParameterBagInterface $parameterBag): Response
    {
        $styleList = $parameterBag->get('style');
        $nbPerPage = $parameterBag->get('artist')['nb_max'];
        $offset = ($page - 1) * $nbPerPage;
        $order = [
            'mixDate' => 'ASC',
            'mixTime' => 'ASC',
        ];

        $artistsList = $artistRepository->findBy(
            [],
            $order,
            $nbPerPage,
            $offset,
        );

        $total = $artistRepository->count();
        $totalPages = ceil($total / $nbPerPage);

        return $this->render('artist/index.html.twig', [
            'artistsList' => $artistsList,
            'page' => $page,
            'totalPages' => $totalPages,
            'link' => 'artist_list',
            'styleList' => $styleList,
        ]);
    }

    #[Route('/filter-style', name: 'filter_style', methods: ['POST'])]
    public function filterStyle(Request $request): Response
    {
        $style = $request->request->get('style');

        if ($style == "all" ) {
            return $this->redirectToRoute('artist_list', ['page' => 1]);
        }

        // Va sur la page 1 du style choisi
        return $this->redirectToRoute('artist_style_list', [
            'style' => $style,
            'page'  => 1,
        ]);
    }

    #[Route('/id/{id}', name: 'id', requirements: ['id' => '\d+'])]
    public function detail(int $id, ArtistRepository $artistRepository): Response
    {
        $artist = $artistRepository->find($id);
        if (!$artist) {
            $this->addFlash('error', 'Artist not found');
            return $this->render('error.html.twig', [
                'status' => 'artist not found',
            ]);
        }
        return $this->render('artist/artist-page.html.twig', [
            'artist' => $artist
        ]);
    }


    #[Route('/style/{style}/page/{page}', name: 'style_list')]
    public function listByStyle(String $style, int $page, ArtistRepository $artistRepository, ParameterBagInterface $parameterBag): Response
    {
        $styleList = $parameterBag->get('style');
        $nbPerPage = $parameterBag->get('artist')['nb_max'];
        $offset = ($page - 1) * $nbPerPage;

        $artistsList = $artistRepository->findByStyleWithDQL(
            $style,
            $nbPerPage,
            $offset
        );

        $total = $artistRepository->countByStyle($style);
        $totalPages = ceil($total / $nbPerPage);


        return $this->render('artist/index.html.twig', [
            'artistsList' => $artistsList,
            'page' => $page,
            'style' => $style,
            'totalPages' => $totalPages,
            'link' => 'artist_style_list',
            'styleList' => $styleList
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addArtist(Request $request, EntityManagerInterface $em): Response
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($artist);
            $em->flush();

            $this->addFlash('success', 'Artist added successfully');

            return $this->redirectToRoute('artist_id', ['id' => $artist->getId()]);
        }

        return $this->render('artist/edit.html.twig', [
            'artist_form' => $form,
        ]);
    }


    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function updateArtist(Artist $artist, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Artist updated successfully');

            return $this->redirectToRoute('artist_id', ['id' => $artist->getId()]);
        }

        return $this->render('artist/edit.html.twig', [
            'artist_form' => $form,
        ]);
    }
}
