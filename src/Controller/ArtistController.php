<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use App\Repository\MusicGenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/artist', name: 'artist_')]
final class ArtistController extends AbstractController
{

//    #[Route('/page/{page}', name: 'list', requirements: ['page' => '\d+',], methods: ['GET'])]
//    public function list(ArtistRepository $artistRepository, int $page, ParameterBagInterface $parameterBag): Response
//    {
//        $styleList = $parameterBag->get('style');
//        $nbPerPage = $parameterBag->get('artist')['nb_max'];
//        $offset = ($page - 1) * $nbPerPage;
//        $order = [
//            'mixDate' => 'ASC',
//            'mixTime' => 'ASC',
//        ];
//
//        $artistsList = $artistRepository->findBy(
//            [],
//            $order,
//            $nbPerPage,
//            $offset,
//        );
//
//        $total = $artistRepository->count();
//        $totalPages = ceil($total / $nbPerPage);
//
//        return $this->render('artist/edit.html.twig', [
//            'artistsList' => $artistsList,
//            'page' => $page,
//            'totalPages' => $totalPages,
//            'link' => 'artist_list',
//            'styleList' => $styleList,
//        ]);
//    }

    #[Route('/page/{page}', name: 'list', requirements: ['page' => '\d+',], methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function list(ArtistRepository $artistRepository, MusicGenreRepository $musicGenreRepository, int $page, ParameterBagInterface $parameterBag): Response
    {
        $styleList = $musicGenreRepository->findAll();
        $nbPerPage = $parameterBag->get('artist')['nb_max'];
        $offset = ($page - 1) * $nbPerPage;
        $order = [
            'mixDate' => 'ASC',
            'mixTime' => 'ASC',
        ];

        $artistsList = $artistRepository->getArtistWithInfos(
            $nbPerPage,
            $offset,
        );

        $total = $artistRepository->count();
        $totalPages = ceil($total / $nbPerPage);

        return $this->render('artist/artist-list.html.twig', [
            'artistsList' => $artistsList,
            'page' => $page,
            'totalPages' => $totalPages,
            'link' => 'artist_list',
            'styleList' => $styleList
        ]);
    }

    #[Route('/filter-style', name: 'filter_style', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function filterStyle(Request $request): Response
    {
        $style = $request->request->get('style');

        if ($style == "all") {
            return $this->redirectToRoute('artist_list', ['page' => 1]);
        }

        // Va sur la page 1 du style choisi
        return $this->redirectToRoute('artist_style_list', [
            'style' => $style,
            'page' => 1,
        ]);
    }

    #[Route('/id/{id}', name: 'id', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
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
    #[IsGranted('ROLE_USER')]
    public function listByStyle(string $style, int $page, ArtistRepository $artistRepository, ParameterBagInterface $parameterBag): Response
    {
        $nbPerPage = $parameterBag->get('artist')['nb_max'];
        $offset = ($page - 1) * $nbPerPage;

        $artistsList = $artistRepository->findByStyleWithDQL(
            $style,
            $nbPerPage,
            $offset
        );

        $total = $artistRepository->countByStyle($style);
        $totalPages = ceil($total / $nbPerPage);

        if ($totalPages == 0) {
            $this->addFlash('error', 'No artist found for this style');
            return $this->redirectToRoute('artist_list', ['page' => 1]);
        }


        return $this->render('artist/artist-list.html.twig', [
            'artistsList' => $artistsList,
            'page' => $page,
            'totalPages' => $totalPages,
            'link' => 'artist_style_list'
        ]);
    }

    #[Route('/add', name: 'add')]
    #[IsGranted('ROLE_ADMIN')]
    public function addArtist(Request $request, EntityManagerInterface $em): Response
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            dd($artist->getMusicGenres()->toArray());
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
    #[IsGranted('ROLE_ADMIN')]
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

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteArtist(Artist $artist, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $artist->getId(), $request->get('token'))) {
            $em->remove($artist);
            $em->flush();
            $this->addFlash('success', 'Artist deleted successfully');
        } else {
            $this->addFlash('error', 'Delete failed');
        }

        return $this->redirectToRoute('artist_list', ['page' => 1]);
    }
}
