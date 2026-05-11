<?php

namespace App\Controller\Admin;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin/evenement')]
#[IsGranted('ROLE_ADMIN')]
final class AdminEvenementController extends AbstractController
{
    #[Route('/', name: 'app_admin_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repository->createQueryBuilder('e')->getQuery();
        
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );
        
        return $this->render('admin/evenement/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_admin_evenement_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        
        $evenement->setTitre($request->request->get('titre'));
        $evenement->setDate(new \DateTime($request->request->get('date')));
        $evenement->setLieu($request->request->get('lieu'));
        
        $entityManager->persist($evenement);
        $entityManager->flush();
        
        $this->addFlash('success', 'Événement ajouté avec succès');
        return $this->redirectToRoute('app_admin_evenement_index');
    }

    #[Route('/{id}/edit', name: 'app_admin_evenement_edit', methods: ['POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $evenement->setTitre($request->request->get('titre'));
        $evenement->setDate(new \DateTime($request->request->get('date')));
        $evenement->setLieu($request->request->get('lieu'));
        
        $entityManager->flush();
        
        $this->addFlash('success', 'Événement modifié avec succès');
        return $this->redirectToRoute('app_admin_evenement_index');
    }

    #[Route('/{id}/delete', name: 'app_admin_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
            $this->addFlash('success', 'Événement supprimé avec succès');
        }
        
        return $this->redirectToRoute('app_admin_evenement_index');
    }
}