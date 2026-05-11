<?php

namespace App\Controller\Front;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use App\Repository\FiliereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'evenement_index')]
    public function index(EvenementRepository $evenementRepository, FiliereRepository $filiereRepository, Request $request): Response
    {
        $categorie = $request->query->get('categorie');
        $filiereId = $request->query->get('filiere');
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 9;
        
        $qb = $evenementRepository->createQueryBuilder('e');
        
        if ($categorie && $categorie !== 'all') {
            $qb->andWhere('e.categorie = :categorie')->setParameter('categorie', $categorie);
        }
        
        if ($filiereId && $filiereId !== 'all') {
            $qb->andWhere('e.filiere = :filiereId')->setParameter('filiereId', $filiereId);
        }
        
        $qb->orderBy('e.date', 'ASC');
        
        $total = count($qb->getQuery()->getResult());
        $totalPages = ceil($total / $limit);
        
        $evenements = $qb->setFirstResult(($page - 1) * $limit)
                         ->setMaxResults($limit)
                         ->getQuery()
                         ->getResult();
        
        $categories = [
            'webinaire' => 'Webinaire',
            'conference' => 'Conference',
            'porte_ouverte' => 'Portes Ouvertes',
            'formation' => 'Formation',
            'mentorat' => 'Mentorat',
            'autre' => 'Autre'
        ];
        
        $filieres = $filiereRepository->findAll();
        
        return $this->render('front/evenement/index.html.twig', [
            'evenements' => $evenements,
            'categories' => $categories,
            'filieres' => $filieres,
            'categorieActive' => $categorie,
            'filiereActive' => $filiereId,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/{id}/inscription', name: 'evenement_inscription', methods: ['POST'])]
    public function inscription(Evenement $evenement, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->json(['success' => false, 'message' => 'Connectez-vous'], 401);
        }
        
        if ($user->getEvenements()->contains($evenement)) {
            return $this->json(['success' => false, 'message' => 'Deja inscrit']);
        }
        
        $user->addEvenement($evenement);
        $em->flush();
        
        return $this->json(['success' => true, 'message' => 'Inscription reussie']);
    }
    
    #[Route('/mes-inscriptions', name: 'mes_inscriptions')]
    public function mesInscriptions(): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('front/evenement/mes_inscriptions.html.twig', [
            'inscriptions' => $user->getEvenements(),
        ]);
    }
}