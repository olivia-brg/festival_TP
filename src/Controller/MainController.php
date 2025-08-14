<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_')]
class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function home(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController'
        ]);
    }

    #[Route('/a-propos', name: 'about')]
    public function apropos(): Response
    {
        return $this->render("about-us.html.twig");
    }
}