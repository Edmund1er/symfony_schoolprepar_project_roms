<?php

namespace App\Controller\Front;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profil')]
#[IsGranted('ROLE_USER')]
class ProfilController extends AbstractController
{
    #[Route('/', name: 'profil_index')]
    public function index(): Response
    {
        $user = $this->getUser();
        
        return $this->render('front/profil/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/modifier', name: 'profil_edit', methods: ['POST'])]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        
        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $email = $request->request->get('email');
        
        if ($nom) $user->setNom($nom);
        if ($prenom) $user->setPrenom($prenom);
        if ($email) $user->setEmail($email);
        
        $em->flush();
        
        $this->addFlash('success', 'Profil mis à jour avec succès');
        return $this->redirectToRoute('profil_index');
    }

    #[Route('/mot-de-passe', name: 'profil_password', methods: ['POST'])]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $oldPassword = $request->request->get('old_password');
        $newPassword = $request->request->get('new_password');
        $confirmPassword = $request->request->get('confirm_password');
        
        // Vérifier l'ancien mot de passe
        if (!$passwordHasher->isPasswordValid($user, $oldPassword)) {
            $this->addFlash('error', 'Ancien mot de passe incorrect');
            return $this->redirectToRoute('profil_index');
        }
        
        // Vérifier la longueur
        if (strlen($newPassword) < 6) {
            $this->addFlash('error', 'Le mot de passe doit contenir au moins 6 caractères');
            return $this->redirectToRoute('profil_index');
        }
        
        // Vérifier la confirmation
        if ($newPassword !== $confirmPassword) {
            $this->addFlash('error', 'Les mots de passe ne correspondent pas');
            return $this->redirectToRoute('profil_index');
        }
        
        // Changer le mot de passe
        $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
        $em->flush();
        
        $this->addFlash('success', 'Mot de passe modifié avec succès');
        return $this->redirectToRoute('profil_index');
    }

    #[Route('/mentor', name: 'profil_mentor_edit', methods: ['POST'])]
    #[IsGranted('ROLE_MENTOR')]
    public function editMentor(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        
        $specialite = $request->request->get('specialite');
        $biographie = $request->request->get('biographie');
        $experience = $request->request->get('experience');
        
        if ($specialite) $user->setSpecialite($specialite);
        if ($biographie) $user->setBiographie($biographie);
        if ($experience) $user->setExperience($experience);
        
        $em->flush();
        
        $this->addFlash('success', 'Profil mentor mis à jour');
        return $this->redirectToRoute('profil_index');
    }
}