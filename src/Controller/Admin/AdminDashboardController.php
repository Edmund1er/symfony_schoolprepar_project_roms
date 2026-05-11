<?php

namespace App\Controller\Admin;

use App\Repository\EvenementRepository;
use App\Repository\FiliereRepository;
use App\Repository\EtablissementRepository;
use App\Repository\UserRepository;
use App\Repository\QuizRepository;
use App\Repository\ForumCategorieRepository;
use App\Repository\MentoratRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
final class AdminDashboardController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard', methods: ['GET'])]
    public function index(
        FiliereRepository $filiereRepository,
        EtablissementRepository $etablissementRepository,
        UserRepository $userRepository,
        EvenementRepository $evenementRepository,
        QuizRepository $quizRepository,
        ForumCategorieRepository $forumCategorieRepository,
        MentoratRepository $mentoratRepository
    ): Response {
        return $this->render('admin/dashboard.html.twig', [
            'filieres_count' => $filiereRepository->count([]),
            'etablissements_count' => $etablissementRepository->count([]),
            'utilisateurs_count' => $userRepository->count([]),
            'evenements_count' => $evenementRepository->count([]),
            'quiz_count' => $quizRepository->count([]),
            'forum_categories_count' => $forumCategorieRepository->count([]),
            'mentorat_count' => $mentoratRepository->count([]),
        ]);
    }
}