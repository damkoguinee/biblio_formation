<?php

namespace App\Controller;

use App\Entity\Abonne;
use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Repository\EmpruntRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/espace-lecteur", name:"app_espace_lecteur")]
#[IsGranted("ROLE_LECTEUR")]
class EspaceLecteurController extends AbstractController
{
    #[Route('/', name: '')]
    public function index(): Response
    {
        return $this->render('espace_lecteur/index.html.twig');
    }

    #[Route('/emprunter-livre-{id}', name: "_emprunter")]
    public function emprunter(Livre $livre, EntityManagerInterface $em)
    {
        /**
         Pour recuperer les informations de l'utilisateur connecté:
            . Twig          :   app.user
            . Controller    :   $this->getUser()
        */
        if ($livre->getDisponible()) {            
            $emprunt = new Emprunt;
            $emprunt->setLivre($livre);
            $emprunt->setAbonne($this->getUser());
            $emprunt->setDateEmprunt(new \dateTime("now"));
    
            $em->persist($emprunt);
            $em->flush();
            $this->addFlash("success", "Confirmation de l'emprunt du livre <em>". $livre->getTitreAuteur(). " </em>");
            return $this->redirectToRoute("app_espace_lecteur");
        }
        $this->addFlash("warning", "vous ne pouvez pas emprunter ce livre");
        return $this->redirectToRoute("app_home");
    }
}
