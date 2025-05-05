<?php

namespace App\Controller;

use App\Entity\NomBoutique;
use App\Entity\ImageB;
use App\Form\NomBoutiqueForm;
use App\Repository\NomBoutiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nom/boutique')]
final class NomBoutiqueController extends AbstractController
{
    #[Route(name: 'app_nom_boutique_index', methods: ['GET'])]
    public function index(NomBoutiqueRepository $nomBoutiqueRepository): Response
    {
        return $this->render('nom_boutique/index.html.twig', [
            'nom_boutiques' => $nomBoutiqueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_nom_boutique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nomBoutique = new NomBoutique();
        $form = $this->createForm(NomBoutiqueForm::class, $nomBoutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('imageb')->getData();//recuperation de l'image ok
            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                // On crée l'image dans la base de données
                $img = new ImageB();
                $img->setNameb($fichier);
                $nomBoutique->addImageB($img);
                
            }
            $entityManager->persist($nomBoutique);
            $entityManager->flush();

            return $this->redirectToRoute('app_nom_boutique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nom_boutique/new.html.twig', [
            'nom_boutique' => $nomBoutique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nom_boutique_show', methods: ['GET'])]
    public function show(NomBoutique $nomBoutique): Response
    {
        return $this->render('nom_boutique/show.html.twig', [
            'nom_boutique' => $nomBoutique,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nom_boutique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NomBoutique $nomBoutique, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NomBoutiqueForm::class, $nomBoutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nom_boutique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nom_boutique/edit.html.twig', [
            'nom_boutique' => $nomBoutique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nom_boutique_delete', methods: ['POST'])]
    public function delete(Request $request, NomBoutique $nomBoutique, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nomBoutique->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($nomBoutique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nom_boutique_index', [], Response::HTTP_SEE_OTHER);
    }
}
