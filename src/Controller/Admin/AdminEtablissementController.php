<?php

namespace App\Controller\Admin;

use App\Entity\Etablissement;
use App\Form\EtablissementType;
use App\Repository\EtablissementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/etablissement')]
final class AdminEtablissementController extends AbstractController
{
    #[Route(name: 'app_admin_etablissement_index', methods: ['GET'])]
    public function index(EtablissementRepository $etablissementRepository): Response
    {
        return $this->render('admin/etablissement/index.html.twig', [
            'etablissements' => $etablissementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_etablissement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $etablissement = new Etablissement();
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Gestion de l'upload de l'image
            $imageFile = $form->get('image')->getData();
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

            return $this->redirectToRoute('app_admin_etablissement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/etablissement/new.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_etablissement_show', methods: ['GET'])]
    public function show(Etablissement $etablissement): Response
    {
        return $this->render('admin/etablissement/show.html.twig', [
            'etablissement' => $etablissement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_etablissement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etablissement $etablissement, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Gestion de l'upload de l'image
            $imageFile = $form->get('image')->getData();
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
            
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_etablissement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/etablissement/edit.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_etablissement_delete', methods: ['POST'])]
    public function delete(Request $request, Etablissement $etablissement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etablissement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($etablissement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_etablissement_index', [], Response::HTTP_SEE_OTHER);
    }
}