<?php

namespace App\Controller\Admin;

use App\Entity\Quiz;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin/quiz')]
#[IsGranted('ROLE_ADMIN')]
class AdminQuizController extends AbstractController
{
    #[Route('/', name: 'app_admin_quiz_index', methods: ['GET'])]
    public function index(QuizRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repository->createQueryBuilder('q')->getQuery();
        
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );
        
        return $this->render('admin/quiz/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_admin_quiz_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $quiz = new Quiz();
        $quiz->setTitre($request->request->get('titre'));
        $quiz->setDescription($request->request->get('description'));
        $quiz->setType($request->request->get('type'));
        $quiz->setDuree($request->request->get('duree'));
        $quiz->setEstActif($request->request->has('estActif'));
        
        $em->persist($quiz);
        $em->flush();
        
        $this->addFlash('success', 'Quiz ajouté');
        return $this->redirectToRoute('app_admin_quiz_index');
    }

    #[Route('/{id}/edit', name: 'app_admin_quiz_edit', methods: ['POST'])]
    public function edit(Request $request, Quiz $quiz, EntityManagerInterface $em): Response
    {
        $quiz->setTitre($request->request->get('titre'));
        $quiz->setDescription($request->request->get('description'));
        $quiz->setType($request->request->get('type'));
        $quiz->setDuree($request->request->get('duree'));
        $quiz->setEstActif($request->request->has('estActif'));
        
        $em->flush();
        
        $this->addFlash('success', 'Quiz modifié');
        return $this->redirectToRoute('app_admin_quiz_index');
    }

    #[Route('/{id}/delete', name: 'app_admin_quiz_delete', methods: ['POST'])]
    public function delete(Request $request, Quiz $quiz, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quiz->getId(), $request->request->get('_token'))) {
            $em->remove($quiz);
            $em->flush();
            $this->addFlash('success', 'Quiz supprimé');
        }
        
        return $this->redirectToRoute('app_admin_quiz_index');
    }
}