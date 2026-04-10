<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FiliereController extends AbstractController
{
    #[Route('/filieres', name: 'filiere_index')]
    public function index(): Response
    {
        return $this->render('front/filiere/index.html.twig');
    }

    #[Route('/filieres/{id}', name: 'filiere_show')]
    public function show(int $id): Response
    {
        return $this->render('front/filiere/show.html.twig', [
            'id' => $id
        ]);
    }
}