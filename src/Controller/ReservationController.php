<?php

namespace App\Controller;

use App\Entity\Livre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface as session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_LECTEUR")]
class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(session $session): Response
    {
        $panier = $session->get("panier", []);
        return $this->render('reservation/index.html.twig', [
            'panier' => $panier,
        ]);
    }
    
    #[Route('/reservation/livre-{id}', name: 'app_reservation_livre')]
    /**
     * Pour la session on utilise la classe SESSIONINTERFACE que j'ai renommé en session
     */
    public function livre(Livre $livre, session $session): Response
    {
        $panier = $session->get("panier", []);
        $dejaReserve = false;
        foreach ($panier as $indice => $item) {
            if ($item['livre']->getId()==$livre->getId()) {
                $dejaReserve= true;
                break;
            }
        }
        if ($dejaReserve) {
            $this->addFlash("warning", "le livre ". $livre->getTitreAuteur()." est déjà dans la liste de vos reservations");
        } else {
            $panier[]=["livre"=>$livre, "date" =>date("d/m/Y")];
            $session->set("panier", $panier); // $_SESSION['panier]=$panier
            $this->addFlash("info", "le livre ". $livre->getTitreAuteur(). " a été ajouté dans la liste de vos réservations");
        }
        return $this->redirectToRoute("app_reservation");
    }
}
