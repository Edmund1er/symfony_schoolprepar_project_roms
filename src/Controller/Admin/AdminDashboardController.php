<?php

namespace App\Controller\Admin;

use App\Repository\EvenementRepository;
use App\Repository\FiliereRepository;
use App\Repository\EtablissementRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class AdminDashboardController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard', methods: ['GET'])]
    public function index(
        FiliereRepository $filiereRepository,
        EtablissementRepository $etablissementRepository,
        UserRepository $userRepository,
        EvenementRepository $evenementRepository
    ): Response {
        return $this->render('admin/dashboard.html.twig', [
            'filieres_count' => $filiereRepository->count([]),
            'etablissements_count' => $etablissementRepository->count([]),
            'utilisateurs_count' => $userRepository->count([]),
            'evenements_count' => $evenementRepository->count([]),
        ]);
    }
}