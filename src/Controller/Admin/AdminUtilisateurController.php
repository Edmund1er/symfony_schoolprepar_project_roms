<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\FiliereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin/utilisateur')]
#[IsGranted('ROLE_ADMIN')]
final class AdminUtilisateurController extends AbstractController
{
    #[Route('/', name: 'app_admin_utilisateur_index', methods: ['GET'])]
    public function index(UserRepository $repository, FiliereRepository $filiereRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repository->createQueryBuilder('u')->getQuery();
        
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );
        
        return $this->render('admin/utilisateur/index.html.twig', [
            'pagination' => $pagination,
            'filieres' => $filiereRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_utilisateur_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FiliereRepository $filiereRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        
        $user->setNom($request->request->get('nom'));
        $user->setPrenom($request->request->get('prenom'));
        $user->setEmail($request->request->get('email'));
        
        $role = json_decode($request->request->get('role'), true);
        $user->setRole($role);
        
        $filiereId = $request->request->get('filiere');
        if ($filiereId) {
            $filiere = $filiereRepository->find($filiereId);
            $user->setFiliere($filiere);
        }
        
        $plainPassword = $request->request->get('password');
        $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        
        $entityManager->persist($user);
        $entityManager->flush();
        
        $this->addFlash('success', 'Utilisateur ajouté avec succès');
        return $this->redirectToRoute('app_admin_utilisateur_index');
    }

    #[Route('/{id}/edit', name: 'app_admin_utilisateur_edit', methods: ['POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, FiliereRepository $filiereRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user->setNom($request->request->get('nom'));
        $user->setPrenom($request->request->get('prenom'));
        $user->setEmail($request->request->get('email'));
        
        $role = json_decode($request->request->get('role'), true);
        $user->setRole($role);
        
        $filiereId = $request->request->get('filiere');
        if ($filiereId) {
            $filiere = $filiereRepository->find($filiereId);
            $user->setFiliere($filiere);
        } else {
            $user->setFiliere(null);
        }
        
        $plainPassword = $request->request->get('password');
        if ($plainPassword) {
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }
        
        $entityManager->flush();
        
        $this->addFlash('success', 'Utilisateur modifié avec succès');
        return $this->redirectToRoute('app_admin_utilisateur_index');
    }

    #[Route('/{id}/delete', name: 'app_admin_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        }
        
        return $this->redirectToRoute('app_admin_utilisateur_index');
    }
}