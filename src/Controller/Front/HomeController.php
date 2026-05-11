<?php

namespace App\Controller\Front;

use App\Repository\FiliereRepository;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'front_home')]
    public function index(FiliereRepository $filiereRepository, EvenementRepository $evenementRepository): Response
    {
        return $this->render('front/home.html.twig', [
            'filieres' => $filiereRepository->findBy([], ['id' => 'DESC'], 3),
            'evenements' => $evenementRepository->findBy([], ['date' => 'ASC'], 3),
        ]);
    }
}