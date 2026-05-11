<?php

namespace App\Controller\Front;

use App\Entity\Etablissement;
use App\Repository\EtablissementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/etablissement')]
final class EtablissementController extends AbstractController
{
    #[Route('/', name: 'etablissement_index', methods: ['GET'])]
    public function index(EtablissementRepository $etablissementRepository): Response
    {
        return $this->render('front/etablissement/index.html.twig', [
            'etablissements' => $etablissementRepository->findBy([], ['id' => 'DESC'], 6),
            'total' => $etablissementRepository->count([]),
        ]);
    }

    #[Route('/load-more', name: 'etablissement_load_more', methods: ['GET'])]
    public function loadMore(Request $request, EtablissementRepository $etablissementRepository): Response
    {
        $offset = $request->query->getInt('offset', 0);
        $limit = 6;
        
        $etablissements = $etablissementRepository->findBy([], ['id' => 'DESC'], $limit, $offset);
        
        $data = [];
        foreach ($etablissements as $etablissement) {
            $data[] = [
                'id' => $etablissement->getId(),
                'nom' => $etablissement->getNom(),
                'adresse' => $etablissement->getAdresse(),
                'email' => $etablissement->getEmail(),
                'image' => $etablissement->getImage(),
            ];
        }
        
        return $this->json(['items' => $data]);
    }

    #[Route('/{id}', name: 'etablissement_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Etablissement $etablissement): Response
    {
        return $this->render('front/etablissement/show.html.twig', [
            'etablissement' => $etablissement,
        ]);
    }
}