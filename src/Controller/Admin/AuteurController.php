<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use App\Form\AuteurType;
use App\Repository\AuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/auteur')]
class AuteurController extends AbstractController
{
    #[Route('/', name: 'app_admin_auteur_index', methods: ['GET'])]
    public function index(AuteurRepository $auteurRepository): Response
    {
        return $this->render('admin/auteur/index.html.twig', [
            'auteurs' => $auteurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_auteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $auteur = new Auteur();
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom= $auteur->getNom();
            $auteur->setNom(strtoupper($nom));
            $entityManager->persist($auteur);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_auteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/auteur/new.html.twig', [
            'entite' => $auteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_auteur_show', methods: ['GET'])]
    public function show(Auteur $auteur): Response
    {
        return $this->render('admin/auteur/show.html.twig', [
            'auteur' => $auteur,
            'entite' => $auteur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_auteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Auteur $auteur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom= $auteur->getNom();
            $auteur->setNom(strtoupper($nom));
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_auteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/auteur/edit.html.twig', [
            'entite' => $auteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_auteur_delete', methods: ['POST'])]
    public function delete(Request $request, Auteur $auteur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auteur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($auteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_auteur_index', [], Response::HTTP_SEE_OTHER);
    }
}
