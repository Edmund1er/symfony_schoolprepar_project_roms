<?php

namespace App\Controller\Front;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Repository\UserRepository;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/message')]
#[IsGranted('ROLE_USER')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'message_inbox')]
    public function inbox(ConversationRepository $conversationRepository): Response
    {
        $conversations = $conversationRepository->findByUser($this->getUser()->getId());
        
        return $this->render('front/message/inbox.html.twig', [
            'conversations' => $conversations,
        ]);
    }

    #[Route('/conversation/{id}', name: 'message_conversation')]
    public function conversation($id, ConversationRepository $conversationRepository, EntityManagerInterface $em): Response
    {
        $conversation = $conversationRepository->find($id);
        
        if (!$conversation || ($conversation->getUser1() !== $this->getUser() && $conversation->getUser2() !== $this->getUser())) {
            $this->addFlash('error', 'Conversation non trouvee');
            return $this->redirectToRoute('message_inbox');
        }
        
        return $this->render('front/message/conversation.html.twig', [
            'conversation' => $conversation,
        ]);
    }

    #[Route('/send/{id}', name: 'message_send', methods: ['POST'])]
    public function send($id, Request $request, ConversationRepository $conversationRepository, EntityManagerInterface $em): JsonResponse
    {
        $conversation = $conversationRepository->find($id);
        $contenu = $request->request->get('contenu');
        
        if (!$conversation || ($conversation->getUser1() !== $this->getUser() && $conversation->getUser2() !== $this->getUser())) {
            return $this->json(['success' => false, 'error' => 'Conversation non trouvee']);
        }
        
        if (empty($contenu)) {
            return $this->json(['success' => false, 'error' => 'Message vide']);
        }
        
        $message = new Message();
        $message->setConversation($conversation);
        $message->setExpediteur($this->getUser());
        $message->setContenu($contenu);
        
        $conversation->setDateDernierMessage(new \DateTime());
        
        $em->persist($message);
        $em->flush();
        
        return $this->json([
            'success' => true,
            'message' => $contenu,
            'time' => (new \DateTime())->format('H:i')
        ]);
    }

    #[Route('/check/{id}', name: 'message_check')]
    public function check($id, Request $request, ConversationRepository $conversationRepository, EntityManagerInterface $em): JsonResponse
    {
        $conversation = $conversationRepository->find($id);
        $lastCount = $request->query->getInt('lastCount', 0);
        
        if (!$conversation || ($conversation->getUser1() !== $this->getUser() && $conversation->getUser2() !== $this->getUser())) {
            return $this->json(['success' => false]);
        }
        
        $messages = $conversation->getMessages();
        $newMessages = [];
        
        if ($messages->count() > $lastCount) {
            for ($i = $lastCount; $i < $messages->count(); $i++) {
                $msg = $messages->get($i);
                if ($msg->getExpediteur() !== $this->getUser()) {
                    $newMessages[] = [
                        'contenu' => $msg->getContenu(),
                        'time' => $msg->getDateEnvoi()->format('H:i'),
                        'expediteurId' => $msg->getExpediteur()->getId()
                    ];
                }
            }
        }
        
        return $this->json([
            'success' => true,
            'newMessages' => $newMessages,
            'newCount' => $messages->count()
        ]);
    }

    #[Route('/nouveau/{id}', name: 'message_new')]
    public function new($id, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $destinataire = $userRepository->find($id);
        
        if (!$destinataire) {
            $this->addFlash('error', 'Utilisateur non trouve');
            return $this->redirectToRoute('mentorat_index');
        }
        
        $existingConversation = $em->getRepository(Conversation::class)->createQueryBuilder('c')
            ->where('(c.user1 = :user AND c.user2 = :dest) OR (c.user1 = :dest AND c.user2 = :user)')
            ->setParameter('user', $this->getUser())
            ->setParameter('dest', $destinataire)
            ->getQuery()
            ->getOneOrNullResult();
        
        if ($existingConversation) {
            return $this->redirectToRoute('message_conversation', ['id' => $existingConversation->getId()]);
        }
        
        $conversation = new Conversation();
        $conversation->setUser1($this->getUser());
        $conversation->setUser2($destinataire);
        
        $em->persist($conversation);
        $em->flush();
        
        return $this->redirectToRoute('message_conversation', ['id' => $conversation->getId()]);
    }
}