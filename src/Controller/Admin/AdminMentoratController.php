<?php

namespace App\Controller\Admin;

use App\Repository\MentoratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin/mentorat')]
#[IsGranted('ROLE_ADMIN')]
class AdminMentoratController extends AbstractController
{
    #[Route('/', name: 'app_admin_mentorat_index', methods: ['GET'])]
    public function index(MentoratRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repository->createQueryBuilder('m')
            ->orderBy('m.demandeLe', 'DESC')
            ->getQuery();
        
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );
        
        return $this->render('admin/mentorat/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/{id}/statut/{statut}', name: 'app_admin_mentorat_changer_statut')]
    public function changerStatut($id, $statut, MentoratRepository $repository, EntityManagerInterface $em): Response
    {
        $mentorat = $repository->find($id);
        
        if ($mentorat && in_array($statut, ['accepte', 'refuse', 'termine'])) {
            $mentorat->setStatut($statut);
            $mentorat->setReponseLe(new \DateTime());
            $em->flush();
            $this->addFlash('success', 'Statut mis à jour');
        }
        
        return $this->redirectToRoute('app_admin_mentorat_index');
    }

    #[Route('/{id}/delete', name: 'app_admin_mentorat_delete', methods: ['POST'])]
    public function delete($id, Request $request, MentoratRepository $repository, EntityManagerInterface $em): Response
    {
        $mentorat = $repository->find($id);
        
        if ($mentorat && $this->isCsrfTokenValid('delete' . $mentorat->getId(), $request->request->get('_token'))) {
            $em->remove($mentorat);
            $em->flush();
            $this->addFlash('success', 'Demande supprimée');
        }
        
        return $this->redirectToRoute('app_admin_mentorat_index');
    }
}