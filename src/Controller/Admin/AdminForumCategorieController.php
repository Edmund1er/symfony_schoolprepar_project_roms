<?php

namespace App\Controller\Admin;

use App\Entity\ForumCategorie;
use App\Repository\ForumCategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin/forum/categorie')]
#[IsGranted('ROLE_ADMIN')]
class AdminForumCategorieController extends AbstractController
{
    #[Route('/', name: 'app_admin_forum_categorie_index', methods: ['GET'])]
    public function index(ForumCategorieRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repository->createQueryBuilder('c')->orderBy('c.ordre', 'ASC')->getQuery();
        
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );
        
        return $this->render('admin/forum_categorie/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_admin_forum_categorie_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $categorie = new ForumCategorie();
        $categorie->setNom($request->request->get('nom'));
        $categorie->setDescription($request->request->get('description'));
        $categorie->setOrdre($request->request->get('ordre') ?: 0);
        
        $em->persist($categorie);
        $em->flush();
        
        $this->addFlash('success', 'Catégorie ajoutée');
        return $this->redirectToRoute('app_admin_forum_categorie_index');
    }

    #[Route('/{id}/edit', name: 'app_admin_forum_categorie_edit', methods: ['POST'])]
    public function edit(Request $request, ForumCategorie $categorie, EntityManagerInterface $em): Response
    {
        $categorie->setNom($request->request->get('nom'));
        $categorie->setDescription($request->request->get('description'));
        $categorie->setOrdre($request->request->get('ordre') ?: 0);
        
        $em->flush();
        
        $this->addFlash('success', 'Catégorie modifiée');
        return $this->redirectToRoute('app_admin_forum_categorie_index');
    }

    #[Route('/{id}/delete', name: 'app_admin_forum_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, ForumCategorie $categorie, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('_token'))) {
            $em->remove($categorie);
            $em->flush();
            $this->addFlash('success', 'Catégorie supprimée');
        }
        
        return $this->redirectToRoute('app_admin_forum_categorie_index');
    }
}