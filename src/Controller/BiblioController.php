<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Repository\EmpruntRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/biblio', name: 'app_biblio')]
class BiblioController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        return $this->render('biblio/index.html.twig', [
            'controller_name' => 'BiblioController',
        ]);
    }

    #[Route('/retour-emprunt-{id}', name: '_retour_emprunt')]
    public function retourConfirm(Emprunt $emprunt, EntityManagerInterface $em)
    {
        $emprunt->setDateRetour(new \dateTime("now"));
        $em->flush();
        $this->addFlash("success","Retour éffectué avec succèe !!");
        return $this->redirectToRoute("app_biblio_retour");
    }

    #[Route('/retour', name: '_retour')]
    public function retour(EmpruntRepository $er)
    {
        $emprunt=$er->findByDateRetourNull();
        return $this->render('biblio/retour.html.twig', [
            'emprunts' => $emprunt,
        ]);
    }

}
