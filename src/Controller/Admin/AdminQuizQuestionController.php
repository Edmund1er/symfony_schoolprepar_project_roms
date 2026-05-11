<?php

namespace App\Controller\Admin;

use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use App\Entity\QuizReponse;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin/quiz/{quizId}/question')]
#[IsGranted('ROLE_ADMIN')]
class AdminQuizQuestionController extends AbstractController
{
    #[Route('/', name: 'app_admin_quiz_question_index')]
    public function index($quizId, QuizRepository $quizRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $quiz = $quizRepository->find($quizId);
        
        $query = $quizRepository->createQueryBuilder('q')
            ->where('q.id = :quizId')
            ->setParameter('quizId', $quizId)
            ->getQuery();
        
        $pagination = $paginator->paginate(
            $quiz->getQuestions(),
            $request->query->getInt('page', 1),
            5
        );
        
        return $this->render('admin/quiz_question/index.html.twig', [
            'quiz' => $quiz,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_admin_quiz_question_new', methods: ['POST'])]
    public function new($quizId, Request $request, QuizRepository $quizRepository, EntityManagerInterface $em): Response
    {
        $quiz = $quizRepository->find($quizId);
        
        $question = new QuizQuestion();
        $question->setQuiz($quiz);
        $question->setTexte($request->request->get('texte'));
        $question->setOrdre($request->request->get('ordre') ?: 0);
        
        $em->persist($question);
        $em->flush();
        
        $reponses = json_decode($request->request->get('reponses', '[]'), true);
        foreach ($reponses as $r) {
            $reponse = new QuizReponse();
            $reponse->setQuestion($question);
            $reponse->setTexte($r['texte']);
            $reponse->setEstCorrecte($r['correcte'] ?? false);
            $reponse->setPoints($r['points'] ?? 0);
            $em->persist($reponse);
        }
        $em->flush();
        
        $this->addFlash('success', 'Question ajoutée');
        return $this->redirectToRoute('app_admin_quiz_question_index', ['quizId' => $quizId]);
    }

    #[Route('/{id}/delete', name: 'app_admin_quiz_question_delete', methods: ['POST'])]
    public function delete($quizId, QuizQuestion $question, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $question->getId(), $request->request->get('_token'))) {
            $em->remove($question);
            $em->flush();
            $this->addFlash('success', 'Question supprimée');
        }
        
        return $this->redirectToRoute('app_admin_quiz_question_index', ['quizId' => $quizId]);
    }
}