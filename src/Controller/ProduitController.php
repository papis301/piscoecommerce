<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitForm;
use App\Entity\ImageP;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/produit')]
final class ProduitController extends AbstractController
{
    #[Route(name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitForm::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('imagep')->getData();//recuperation de l'image ok
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
                $img = new ImageP();
                $img->setNamep($fichier);
                $produit->addImageP($img);
                
            }
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/unproduit.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_showe', methods: ['GET'])]
    public function showe(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitForm::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
