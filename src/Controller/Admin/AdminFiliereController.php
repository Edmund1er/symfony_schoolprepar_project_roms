<?php

namespace App\Controller\Admin;

use App\Entity\Filiere;
use App\Repository\FiliereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin/filiere')]
#[IsGranted('ROLE_ADMIN')]
final class AdminFiliereController extends AbstractController
{
    #[Route('/', name: 'app_admin_filiere_index', methods: ['GET'])]
    public function index(FiliereRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repository->createQueryBuilder('f')->getQuery();
        
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );
        
        return $this->render('admin/filiere/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_admin_filiere_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $filiere = new Filiere();
        
        $filiere->setNom($request->request->get('nom'));
        $filiere->setDescription($request->request->get('description'));
        
        $imageFile = $request->files->get('image');
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
            
            try {
                $imageFile->move(
                    $this->getParameter('filiere_images_directory'),
                    $newFilename
                );
                $filiere->setImage($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors du téléchargement de l\'image');
            }
        }
        
        $entityManager->persist($filiere);
        $entityManager->flush();
        
        $this->addFlash('success', 'Filière ajoutée avec succès');
        return $this->redirectToRoute('app_admin_filiere_index');
    }

    #[Route('/{id}/edit', name: 'app_admin_filiere_edit', methods: ['POST'])]
    public function edit(Request $request, Filiere $filiere, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $filiere->setNom($request->request->get('nom'));
        $filiere->setDescription($request->request->get('description'));
        
        $imageFile = $request->files->get('image');
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
            
            try {
                $imageFile->move(
                    $this->getParameter('filiere_images_directory'),
                    $newFilename
                );
                $oldImage = $filiere->getImage();
                if ($oldImage) {
                    $oldPath = $this->getParameter('filiere_images_directory').'/'.$oldImage;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $filiere->setImage($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors du téléchargement de l\'image');
            }
        }
        
        $entityManager->flush();
        
        $this->addFlash('success', 'Filière modifiée avec succès');
        return $this->redirectToRoute('app_admin_filiere_index');
    }

    #[Route('/{id}/delete', name: 'app_admin_filiere_delete', methods: ['POST'])]
    public function delete(Request $request, Filiere $filiere, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$filiere->getId(), $request->request->get('_token'))) {
            $oldImage = $filiere->getImage();
            if ($oldImage) {
                $oldPath = $this->getParameter('filiere_images_directory').'/'.$oldImage;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $entityManager->remove($filiere);
            $entityManager->flush();
            $this->addFlash('success', 'Filière supprimée avec succès');
        }
        
        return $this->redirectToRoute('app_admin_filiere_index');
    }
}