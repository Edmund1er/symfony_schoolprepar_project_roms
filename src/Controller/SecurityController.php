<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData())
            );
            
            // Définir le rôle selon le choix de l'utilisateur
            $roleChoice = $form->get('roleChoice')->getData();
            if ($roleChoice === 'ROLE_MENTOR') {
                $user->setRole(['ROLE_MENTOR', 'ROLE_USER']);
                // Initialiser les champs mentor vides
                $user->setSpecialite(null);
                $user->setBiographie(null);
                $user->setExperience(null);
                $user->setNoteMoyenne(null);
                $user->setNbAvis(null);
            } else {
                $user->setRole(['ROLE_ELEVE', 'ROLE_USER']);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToTarget();
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank.');
    }

    // Redirection selon le rôle après connexion
    private function redirectToTarget(): RedirectResponse
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Admin
        if (in_array('ROLE_ADMIN', $user->getRole())) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        // Mentor
        if (in_array('ROLE_MENTOR', $user->getRole())) {
            // Rediriger vers les demandes reçues ou dashboard mentor
            return $this->redirectToRoute('mentorat_requetes');
        }

        // Élève par défaut
        return $this->redirectToRoute('front_home');
    }
}