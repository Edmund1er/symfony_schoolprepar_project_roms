<?php

namespace App\Controller\Front;

use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/notifications')]
#[IsGranted('ROLE_USER')]
class NotificationController extends AbstractController
{
    #[Route('/', name: 'notification_index')]
    public function index(NotificationRepository $notificationRepository): Response
    {
        $notifications = $notificationRepository->findBy(
            ['user' => $this->getUser()],
            ['dateCreation' => 'DESC']
        );
        
        return $this->render('front/notification/index.html.twig', [
            'notifications' => $notifications,
        ]);
    }

    #[Route('/{id}/lue', name: 'notification_lue')]
    public function marquerLue($id, NotificationRepository $notificationRepository, EntityManagerInterface $em): Response
    {
        $notification = $notificationRepository->find($id);
        
        if ($notification && $notification->getUser() === $this->getUser()) {
            $notification->setEstLue(true);
            $em->flush();
        }
        
        return $this->redirectToRoute('notification_index');
    }

    #[Route('/tout-lire', name: 'notification_tout_lire')]
    public function toutLire(NotificationRepository $notificationRepository, EntityManagerInterface $em): Response
    {
        $notifications = $notificationRepository->findBy([
            'user' => $this->getUser(),
            'estLue' => false
        ]);
        
        foreach ($notifications as $notification) {
            $notification->setEstLue(true);
        }
        $em->flush();
        
        $this->addFlash('success', 'Toutes les notifications ont été marquées comme lues');
        return $this->redirectToRoute('notification_index');
    }
}