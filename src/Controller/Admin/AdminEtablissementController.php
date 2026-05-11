<?php

namespace App\Controller\Admin;

use App\Entity\Etablissement;
use App\Repository\EtablissementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin/etablissement')]
#[IsGranted('ROLE_ADMIN')]
final class AdminEtablissementController extends AbstractController
{
    #[Route('/', name: 'app_admin_etablissement_index', methods: ['GET'])]
    public function index(EtablissementRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repository->createQueryBuilder('e')->getQuery();
        
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );
        
        return $this->render('admin/etablissement/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_admin_etablissement_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $etablissement = new Etablissement();
        
        $etablissement->setNom($request->request->get('nom'));
        $etablissement->setAdresse($request->request->get('adresse'));
        $etablissement->setEmail($request->request->get('email'));
        
        $imageFile = $request->files->get('image');
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
            
            try {
                $imageFile->move(
                    $this->getParameter('etablissement_images_directory'),
                    $newFilename
                );
                $etablissement->setImage($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors du téléchargement de l\'image');
            }
        }
        
        $entityManager->persist($etablissement);
        $entityManager->flush();
        
        $this->addFlash('success', 'Établissement ajouté avec succès');
        return $this->redirectToRoute('app_admin_etablissement_index');
    }

    #[Route('/{id}/edit', name: 'app_admin_etablissement_edit', methods: ['POST'])]
    public function edit(Request $request, Etablissement $etablissement, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $etablissement->setNom($request->request->get('nom'));
        $etablissement->setAdresse($request->request->get('adresse'));
        $etablissement->setEmail($request->request->get('email'));
        
        $imageFile = $request->files->get('image');
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
            
            try {
                $imageFile->move(
                    $this->getParameter('etablissement_images_directory'),
                    $newFilename
                );
                $oldImage = $etablissement->getImage();
                if ($oldImage) {
                    $oldPath = $this->getParameter('etablissement_images_directory').'/'.$oldImage;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $etablissement->setImage($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors du téléchargement de l\'image');
            }
        }
        
        $entityManager->flush();
        
        $this->addFlash('success', 'Établissement modifié avec succès');
        return $this->redirectToRoute('app_admin_etablissement_index');
    }

    #[Route('/{id}/delete', name: 'app_admin_etablissement_delete', methods: ['POST'])]
    public function delete(Request $request, Etablissement $etablissement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etablissement->getId(), $request->request->get('_token'))) {
            $oldImage = $etablissement->getImage();
            if ($oldImage) {
                $oldPath = $this->getParameter('etablissement_images_directory').'/'.$oldImage;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $entityManager->remove($etablissement);
            $entityManager->flush();
            $this->addFlash('success', 'Établissement supprimé avec succès');
        }
        
        return $this->redirectToRoute('app_admin_etablissement_index');
    }
}