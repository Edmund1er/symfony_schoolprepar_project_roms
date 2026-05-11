<?php

namespace App\Controller\Front;

use App\Entity\Quiz;
use App\Entity\UserQuizResult;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/quiz')]
class QuizController extends AbstractController
{
    #[Route('/', name: 'quiz_index')]
    public function index(QuizRepository $quizRepository): Response
    {
        $quizs = $quizRepository->findBy(['estActif' => true], ['id' => 'ASC']);
        
        return $this->render('front/quiz/index.html.twig', [
            'quizs' => $quizs,
        ]);
    }

    #[Route('/mes-resultats', name: 'quiz_mes_resultats')]
    #[IsGranted('ROLE_USER')]
    public function mesResultats(EntityManagerInterface $em): Response
    {
        $results = $em->getRepository(UserQuizResult::class)->findBy(
            ['user' => $this->getUser()],
            ['dateCompletion' => 'DESC']
        );
        
        return $this->render('front/quiz/mes_resultats.html.twig', [
            'results' => $results,
        ]);
    }

    #[Route('/{id}', name: 'quiz_show')]
    public function show(Quiz $quiz): Response
    {
        return $this->render('front/quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    #[Route('/{id}/start', name: 'quiz_start')]
    #[IsGranted('ROLE_USER')]
    public function start(Quiz $quiz, EntityManagerInterface $em): Response
    {
        $existingResult = $em->getRepository(UserQuizResult::class)->findOneBy([
            'user' => $this->getUser(),
            'quiz' => $quiz
        ]);
        
        return $this->render('front/quiz/start.html.twig', [
            'quiz' => $quiz,
            'existingResult' => $existingResult,
        ]);
    }

    #[Route('/{id}/submit', name: 'quiz_submit', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function submit($id, Request $request, QuizRepository $quizRepository, EntityManagerInterface $em): Response
    {
        $quiz = $quizRepository->find($id);
        
        if (!$quiz) {
            $this->addFlash('error', 'Quiz non trouvé');
            return $this->redirectToRoute('quiz_index');
        }
        
        $existingResult = $em->getRepository(UserQuizResult::class)->findOneBy([
            'user' => $this->getUser(),
            'quiz' => $quiz
        ]);
        
        if ($existingResult) {
            $this->addFlash('warning', 'Vous avez déjà participé à ce quiz');
            return $this->redirectToRoute('quiz_index');
        }
        
        $reponses = $request->request->all();
        $score = 0;
        $totalPoints = 0;
        $details = [];
        
        foreach ($quiz->getQuestions() as $question) {
            $userReponseId = $reponses['question_' . $question->getId()] ?? null;
            $questionPoints = 0;
            
            foreach ($question->getReponses() as $reponse) {
                if ($reponse->getPoints() > 0 && $userReponseId == $reponse->getId()) {
                    $score += $reponse->getPoints();
                    $questionPoints = $reponse->getPoints();
                    $details[] = [
                        'question' => $question->getTexte(),
                        'correcte' => true,
                        'points' => $reponse->getPoints()
                    ];
                    break;
                }
            }
            
            if ($questionPoints == 0 && $userReponseId) {
                $details[] = [
                    'question' => $question->getTexte(),
                    'correcte' => false,
                    'points' => 0
                ];
            }
            
            $totalPoints += 100;
        }
        
        $percentage = $totalPoints > 0 ? round(($score / $totalPoints) * 100) : 0;
        
        $result = new UserQuizResult();
        $result->setUser($this->getUser());
        $result->setQuiz($quiz);
        $result->setScore($score);
        $result->setTotalPoints($totalPoints);
        $result->setDateCompletion(new \DateTime());
        $result->setResultat([
            'score' => $score,
            'total' => $totalPoints,
            'percentage' => $percentage,
            'details' => $details
        ]);
        
        $em->persist($result);
        $em->flush();
        
        $this->addFlash('success', 'Quiz terminé ! Votre score est de ' . $percentage . '%');
        
        return $this->redirectToRoute('quiz_result', ['id' => $result->getId()]);
    }

    #[Route('/resultat/{id}', name: 'quiz_result')]
    #[IsGranted('ROLE_USER')]
    public function result(UserQuizResult $result): Response
    {
        if ($result->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Accès non autorisé');
            return $this->redirectToRoute('quiz_index');
        }
        
        return $this->render('front/quiz/result.html.twig', [
            'result' => $result,
        ]);
    }
}