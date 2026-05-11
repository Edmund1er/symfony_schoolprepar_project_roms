<?php

namespace App\Controller\Front;

use App\Entity\Parcours;
use App\Repository\ParcoursRepository;
use App\Repository\FiliereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/parcours')]
#[IsGranted('ROLE_USER')]
class ParcoursController extends AbstractController
{
    #[Route('/', name: 'parcours_index')]
    public function index(ParcoursRepository $parcoursRepository): Response
    {
        $parcours = $parcoursRepository->findByUser($this->getUser()->getId());
        
        return $this->render('front/parcours/index.html.twig', [
            'parcours' => $parcours,
        ]);
    }

    #[Route('/generer', name: 'parcours_generer')]
    public function generer(FiliereRepository $filiereRepository, EntityManagerInterface $em): Response
    {
        $parcours = new Parcours();
        $parcours->setUser($this->getUser());
        $parcours->setTitre('Mon parcours personnalisé');
        $parcours->setDateCreation(new \DateTime());
        
        $em->persist($parcours);
        $em->flush();
        
        $this->addFlash('success', 'Parcours généré avec succès');
        return $this->redirectToRoute('parcours_show', ['id' => $parcours->getId()]);
    }

    #[Route('/{id}', name: 'parcours_show')]
    public function show(Parcours $parcours): Response
    {
        if ($parcours->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Accès non autorisé');
            return $this->redirectToRoute('parcours_index');
        }
        
        return $this->render('front/parcours/show.html.twig', [
            'parcours' => $parcours,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'parcours_delete', methods: ['POST'])]
    public function delete(Parcours $parcours, Request $request, EntityManagerInterface $em): Response
    {
        if ($parcours->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Accès non autorisé');
            return $this->redirectToRoute('parcours_index');
        }
        
        if ($this->isCsrfTokenValid('delete' . $parcours->getId(), $request->request->get('_token'))) {
            $em->remove($parcours);
            $em->flush();
            $this->addFlash('success', 'Parcours supprimé');
        }
        
        return $this->redirectToRoute('parcours_index');
    }
}