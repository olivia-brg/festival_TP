<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wish', name: 'wish_')]
final class WishController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findAll();
        return $this->render('wish/index.html.twig', [
            'wishes' => $wishes,
        ]);
    }

    #[Route('/idea/{id}', name: 'idea', requirements: ['id'=>'\d+'])]
    public function detail(int $id, WishRepository $wishRepository): Response {
        $wish = $wishRepository->find($id);
        return $this->render('wish/wish-page.html.twig', ['wish' => $wish]);
    }
}
