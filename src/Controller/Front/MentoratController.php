<?php

namespace App\Controller\Front;

use App\Entity\Mentorat;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\MentoratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/mentorat')]
class MentoratController extends AbstractController
{
    /**
     * Liste des mentors disponibles
     */
    #[Route('/', name: 'mentorat_index')]
    public function index(UserRepository $userRepository): Response
    {
        $allUsers = $userRepository->findAll();
        $mentors = [];
        
        foreach ($allUsers as $user) {
            if (in_array('ROLE_MENTOR', $user->getRole())) {
                $mentors[] = $user;
            }
        }
        
        return $this->render('front/mentorat/index.html.twig', [
            'mentors' => $mentors,
        ]);
    }

    /**
     * Demander un mentor (eleve)
     */
    #[Route('/demander/{id}', name: 'mentorat_demander', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function demander($id, Request $request, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        
        // Verifier que l'utilisateur n'est pas un mentor
        if (in_array('ROLE_MENTOR', $user->getRole())) {
            $this->addFlash('error', 'Vous etes mentor, vous ne pouvez pas demander un mentor');
            return $this->redirectToRoute('mentorat_index');
        }
        
        $mentor = $userRepository->find($id);
        
        if (!$mentor || !in_array('ROLE_MENTOR', $mentor->getRole())) {
            $this->addFlash('error', 'Mentor non trouve');
            return $this->redirectToRoute('mentorat_index');
        }
        
        // Verifier si une demande existe deja
        $existingMentorat = $em->getRepository(Mentorat::class)->findOneBy([
            'eleve' => $user,
            'mentor' => $mentor,
        ]);
        
        if ($existingMentorat) {
            $status = $existingMentorat->getStatut();
            if ($status == 'en_attente') {
                $this->addFlash('warning', 'Une demande est deja en attente');
            } elseif ($status == 'accepte') {
                $this->addFlash('warning', 'Vous etes deja en relation avec ce mentor');
            } elseif ($status == 'refuse') {
                $this->addFlash('error', 'Votre demande a ete refusee');
            }
            return $this->redirectToRoute('mentorat_index');
        }
        
        $message = $request->request->get('message');
        
        $mentorat = new Mentorat();
        $mentorat->setEleve($user);
        $mentorat->setMentor($mentor);
        $mentorat->setStatut('en_attente');
        $mentorat->setMessage($message);
        $mentorat->setDemandeLe(new \DateTime());
        
        $em->persist($mentorat);
        $em->flush();
        
        $this->addFlash('success', 'Votre demande de mentorat a ete envoyee');
        return $this->redirectToRoute('mentorat_mes_demandes');
    }

    /**
     * Mes demandes (eleve)
     */
    #[Route('/mes-demandes', name: 'mentorat_mes_demandes')]
    #[IsGranted('ROLE_USER')]
    public function mesDemandes(MentoratRepository $mentoratRepository): Response
    {
        $user = $this->getUser();
        
        $demandes = $mentoratRepository->findBy(['eleve' => $user], ['demandeLe' => 'DESC']);
        
        return $this->render('front/mentorat/mes_demandes.html.twig', [
            'demandes' => $demandes,
        ]);
    }

    /**
     * Demandes recues (mentor)
     */
    #[Route('/requetes', name: 'mentorat_requetes')]
    #[IsGranted('ROLE_MENTOR')]
    public function requetes(MentoratRepository $mentoratRepository): Response
    {
        $user = $this->getUser();
        
        $requetes = $mentoratRepository->findBy(['mentor' => $user], ['demandeLe' => 'DESC']);
        
        return $this->render('front/mentorat/requetes.html.twig', [
            'requetes' => $requetes,
        ]);
    }

    /**
     * Repondre a une demande (mentor)
     */
    #[Route('/repondre/{id}/{statut}', name: 'mentorat_repondre')]
    #[IsGranted('ROLE_MENTOR')]
    public function repondre($id, $statut, MentoratRepository $mentoratRepository, EntityManagerInterface $em): Response
    {
        $mentorat = $mentoratRepository->find($id);
        
        if (!$mentorat || $mentorat->getMentor() !== $this->getUser()) {
            $this->addFlash('error', 'Demande non trouvee');
            return $this->redirectToRoute('mentorat_requetes');
        }
        
        if (!in_array($statut, ['accepte', 'refuse'])) {
            $this->addFlash('error', 'Statut invalide');
            return $this->redirectToRoute('mentorat_requetes');
        }
        
        $mentorat->setStatut($statut);
        $mentorat->setReponseLe(new \DateTime());
        $em->flush();
        
        $message = $statut === 'accepte' ? 'Demande acceptee' : 'Demande refusee';
        $this->addFlash('success', $message);
        
        return $this->redirectToRoute('mentorat_requetes');
    }

    /**
     * Verifier si l'utilisateur peut envoyer un message au mentor
     */
    #[Route('/verifier-relation/{id}', name: 'mentorat_verifier_relation')]
    #[IsGranted('ROLE_USER')]
    public function verifierRelation($id, UserRepository $userRepository, MentoratRepository $mentoratRepository): JsonResponse
    {
        $user = $this->getUser();
        $mentor = $userRepository->find($id);
        
        if (!$mentor) {
            return $this->json(['peutEnvoyerMessage' => false]);
        }
        
        $mentorat = $mentoratRepository->findOneBy([
            'eleve' => $user,
            'mentor' => $mentor,
            'statut' => 'accepte'
        ]);
        
        return $this->json([
            'peutEnvoyerMessage' => $mentorat !== null
        ]);
    }
}