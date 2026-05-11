<?php

namespace App\Controller\Front;

use App\Entity\ForumSujet;
use App\Entity\ForumMessage;
use App\Repository\ForumCategorieRepository;
use App\Repository\ForumSujetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/forum')]
class ForumController extends AbstractController
{
    // Route 1: Liste des categories
    #[Route('/', name: 'forum_index')]
    public function index(ForumCategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findBy([], ['ordre' => 'ASC']);
        
        return $this->render('front/forum/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    // Route 2: Liste des sujets d'une categorie
    #[Route('/categorie/{id}', name: 'forum_categorie')]
    public function categorie($id, ForumCategorieRepository $categorieRepository, ForumSujetRepository $sujetRepository): Response
    {
        $categorie = $categorieRepository->find($id);
        
        if (!$categorie) {
            $this->addFlash('error', 'Categorie non trouvee');
            return $this->redirectToRoute('forum_index');
        }
        
        $sujets = $sujetRepository->findBy(
            ['categorie' => $categorie],
            ['isEpingle' => 'DESC', 'dateCreation' => 'DESC']
        );
        
        return $this->render('front/forum/categorie.html.twig', [
            'categorie' => $categorie,
            'sujets' => $sujets,
        ]);
    }

    // Route 3: Formulaire pour creer un nouveau sujet
    #[Route('/sujet/nouveau', name: 'forum_sujet_new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, ForumCategorieRepository $categorieRepository, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $titre = $request->request->get('titre');
            $contenu = $request->request->get('contenu');
            $categorieId = $request->request->get('categorie');
            
            $categorie = $categorieRepository->find($categorieId);
            
            if (!$categorie) {
                $this->addFlash('error', 'Categorie invalide');
                return $this->redirectToRoute('forum_sujet_new');
            }
            
            if (empty($titre) || empty($contenu)) {
                $this->addFlash('error', 'Titre et contenu obligatoires');
                return $this->redirectToRoute('forum_sujet_new');
            }
            
            $sujet = new ForumSujet();
            $sujet->setTitre($titre);
            $sujet->setContenu($contenu);
            $sujet->setAuteur($this->getUser());
            $sujet->setCategorie($categorie);
            $sujet->setDateCreation(new \DateTime());
            
            $em->persist($sujet);
            $em->flush();
            
            $this->addFlash('success', 'Sujet cree avec succes');
            return $this->redirectToRoute('forum_sujet_show', ['id' => $sujet->getId()]);
        }
        
        $categories = $categorieRepository->findBy([], ['ordre' => 'ASC']);
        
        return $this->render('front/forum/new.html.twig', [
            'categories' => $categories,
        ]);
    }

    // Route 4: Afficher un sujet et ses messages
    #[Route('/sujet/{id}', name: 'forum_sujet_show')]
    public function show($id, ForumSujetRepository $sujetRepository): Response
    {
        $sujet = $sujetRepository->find($id);
        
        if (!$sujet) {
            $this->addFlash('error', 'Sujet non trouve');
            return $this->redirectToRoute('forum_index');
        }
        
        return $this->render('front/forum/sujet.html.twig', [
            'sujet' => $sujet,
        ]);
    }

    // Route 5: Repondre a un sujet
    #[Route('/sujet/{id}/repondre', name: 'forum_sujet_repondre', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function reply($id, Request $request, ForumSujetRepository $sujetRepository, EntityManagerInterface $em): Response
    {
        $sujet = $sujetRepository->find($id);
        
        if (!$sujet) {
            $this->addFlash('error', 'Sujet non trouve');
            return $this->redirectToRoute('forum_index');
        }
        
        $contenu = $request->request->get('contenu');
        
        if (empty($contenu)) {
            $this->addFlash('error', 'Message vide');
            return $this->redirectToRoute('forum_sujet_show', ['id' => $id]);
        }
        
        $message = new ForumMessage();
        $message->setContenu($contenu);
        $message->setAuteur($this->getUser());
        $message->setSujet($sujet);
        $message->setDateCreation(new \DateTime());
        
        $sujet->setDateDernierMessage(new \DateTime());
        
        $em->persist($message);
        $em->flush();
        
        $this->addFlash('success', 'Reponse ajoutee');
        return $this->redirectToRoute('forum_sujet_show', ['id' => $id]);
    }
}