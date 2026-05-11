<?php

namespace App\Controller\Front;

use App\Repository\RecommandationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/recommandations')]
#[IsGranted('ROLE_USER')]
class RecommandationController extends AbstractController
{
    #[Route('/', name: 'recommandation_index')]
    public function index(RecommandationRepository $recommandationRepository): Response
    {
        $recommandations = $recommandationRepository->findByUserNotVue($this->getUser()->getId());
        
        return $this->render('front/recommandation/index.html.twig', [
            'recommandations' => $recommandations,
        ]);
    }

    #[Route('/{id}/vue', name: 'recommandation_vue')]
    public function marquerVue($id, RecommandationRepository $recommandationRepository, EntityManagerInterface $em): Response
    {
        $recommandation = $recommandationRepository->find($id);
        
        if ($recommandation && $recommandation->getUser() === $this->getUser()) {
            $recommandation->setEstVue(true);
            $em->flush();
        }
        
        return $this->redirectToRoute('recommandation_index');
    }
}