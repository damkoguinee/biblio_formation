<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Auteur;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AuteurRepository;
use App\Form\AuteurFormType;

class AuteurController extends AbstractController
{
    /**
        La classe Request permet de gérer les informations de la requête HTTP.
        Dans un objet de cette classe, on va aussi retrouver toutes les valeurs des variables super-globales de PHP.
        à chaque variable superglobale correspond une propriété publique de l'objet Request : 
            query       correspond à        $_GET
            request     correspond à        $_POST
            files                           $_FILES
            session                         $_SESSION
            cookies                         $_COOKIES
            server                          $_SERVER

        Ces propriétés sont des objets qui ont des méthodes pour accéder aux valeurs :
            get(indice)   pour récupérer une valeur de l'indice 
                par exemple $_POST["nom"]  sera récupéré avec $request->request->get("nom")

            has(indice)   pour savoir si l'indice existe
        
        L'objet Request a aussi des méthodes, par exemple :
            isMethod("POST")  pour savoir si la méthode HTTP correspond à la méthode POST
                                (PUT, DELETE, GET, POST)
     */

    #[Route('/auteur', name: 'app_auteur')]
    public function index(AuteurRepository $auteurRepository): Response
    {
        $listeAuteurs = $auteurRepository->findAll();
        return $this->render('auteur/index.html.twig', [
            'auteurs' => $listeAuteurs,
        ]);
    }

    #[Route('/auteur/ajouter', name: 'app_auteur_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        if( $request->isMethod("POST") ) {
            $prenom = $request->request->get("prenom");  // $prenom = $_POST["prenom"]
            $nom = $request->request->get("nom"); 
            $bio = $request->request->get("bio"); 
            $naissance = $request->request->get("naissance"); 
            
            $auteur = new Auteur;
            $auteur->setPrenom($prenom);
            $auteur->setNom($nom);
            $auteur->setBio($bio);
            $auteur->setNaissance(new \DateTime($naissance) );

            /**
                La classe EntityManagerInterface va permettre d'exécuter des requetes SQL du type LMD (Langage de 
                Manipulation des Données) : INSERT, UPDATE, DELETE
                Cet objet EntityManagerInterface utilise toujours des objets entités pour exécuter ces requêtes.
                La méthode 'persist' qui prend un objet entité comme argument, permet de mettre en attente une requête INSERT qui
                sera créé à partir des valeurs de l'objet entité.
             */
            $entityManager->persist($auteur);
            /**
                La méthode 'flush' permet d'exécuter toutes les requêtes SQL en attente.
             */
            $entityManager->flush();
            return $this->redirectToRoute("app_auteur");
        }


        
        return $this->render('auteur/formulaire.html.twig');
    }

    #[Route("/auteur/modifier/{id}", name: 'app_auteur_modifier', requirements:["id" => "\d+"])]
    public function modifier(int $id, AuteurRepository $auteurRepository, Request $rq, EntityManagerInterface $entityManager)
    {
        /**
            On récupère l'auteur dans la bdd grâce à son identifiant (récupéré dans l'URL) en 
            utilisant la classe Repository et la méthode 'find'
         */
        $auteur = $auteurRepository->find($id);
        if ($rq->isMethod("POST")) {
            $auteur->setPrenom($rq->request->get("prenom"));
            $auteur->setNom($rq->request->get("nom"));
            $auteur->setBio($rq->request->get("bio"));
            $auteur->setNaissance(new \DateTime($rq->request->get("naissance")));

            $entityManager->flush();
            return $this->redirectToRoute("app_auteur");
        }
        // dd($auteur);
        return $this->render('auteur/formulaire.html.twig', ["auteur" => $auteur]);
        
    }
    
    #[Route('/auteur/nouveau', name:'app_auteur_nouveau')]
    public function nouveau(Request $rq, EntityManagerInterface $em)
    {
        $auteur=new Auteur;
        $form=$this->createForm(AuteurFormType::class,$auteur);
        /**
         la methode 'handleRequest' permet à la variable $form de gerer les informations venant de la requete HTTP (en utilsant l'objet de la classe request)
         l'objet entité $auteur etant lié au formulaire, toutes les données du formulaires vont automatiquement modifier ses propriétés
         */
        $form->handleRequest($rq);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist(($auteur));
            $em->flush();
            return $this->redirectToRoute(("app_auteur"));
        }
        return $this->render("auteur/form.html.twig", [
            "formAuteur"=>$form->createView()
        ]);
    }

    #[Route('/auteur/supprimer/{id}', name:'app_auteur_supprimer', requirements:["id"=>"\d+"])]
    public function supprimer(int $id, Request $rq, AuteurRepository $auteurRepository, EntityManagerInterface $em)
    {
        $auteur = $auteurRepository->find($id);
        if ($rq->isMethod("POST")) {
            
            $em->remove($auteur);
            $em->flush();
            return $this->redirectToRoute("app_auteur");
        }
        return $this->render("auteur/confirmation.html.twig",[
            "auteur"    =>$auteur
        ]);
    }


}
