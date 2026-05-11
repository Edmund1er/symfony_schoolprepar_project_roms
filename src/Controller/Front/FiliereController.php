<?php

namespace App\Controller\Front;

use App\Entity\Filiere;
use App\Repository\FiliereRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/filiere')]
class FiliereController extends AbstractController
{
    #[Route('/', name: 'filiere_index', methods: ['GET'])]
    public function index(FiliereRepository $filiereRepository): Response
    {
        return $this->render('front/filiere/index.html.twig', [
            'filieres' => $filiereRepository->findBy([], ['id' => 'DESC'], 6),
            'total' => $filiereRepository->count([]),
        ]);
    }

    #[Route('/{id}', name: 'front_filiere_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Filiere $filiere): Response
    {
        return $this->render('front/filiere/show.html.twig', [
            'filiere' => $filiere,
        ]);
    }

    #[Route('/load-more', name: 'filiere_load_more', methods: ['GET'])]
    public function loadMore(Request $request, FiliereRepository $filiereRepository): JsonResponse
    {
        $offset = $request->query->getInt('offset', 0);
        $limit = 6;
        
        $filieres = $filiereRepository->findBy([], ['id' => 'DESC'], $limit, $offset);
        
        $data = [];
        foreach ($filieres as $filiere) {
            $data[] = [
                'id' => $filiere->getId(),
                'nom' => $filiere->getNom(),
                'description' => $filiere->getDescription(),
                'image' => $filiere->getImage(),
            ];
        }
        
        return $this->json(['items' => $data]);
    }
}