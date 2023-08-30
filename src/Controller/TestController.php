<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Cast\Int_;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    #[IsGranted("ROLE_ADMIN")]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/test/nouvelle-route', name: 'app_test_nouvelle_route')]
    public function nouvelleRoute(): Response
    {
        return $this->render('test/index.html.twig',["controller_name"=>"TestController"]);
    }

    #[Route('/test/bonjour', name: 'app_test_bonjour')]
    public function bonjour(): Response
    {
        return $this->render('test/bonjour.html.twig');
    }

    #[Route("/test/calcul/{nombre1}/{nombre2}", name:"app_test_calcul")]
    public function calcul($nombre1,$nombre2):Response
    {
        return $this->render("test/addition.html.twig",[
            "nb1"=>$nombre1,
            "nb2"=>$nombre2,
        ]);
    }

    #[Route("/test/boucle/{int}", name:"app_test_boucle", requirements:["int"=>"[0-9]+"]) ]
    public function boucle(int $int)
    {
        return $this->render("test/loop.html.twig", [
            "int"=>$int
        ]);
    }

    /**
     * methode pour apprendre les tableaux
     */

    #[Route("/test/tableau",name:"app_test_tableau")]
    public function tableau()
    {
        $personne=[
            "nom"       => "CERIEN",
            "prenom"    => "Jean",
            "age"       => 32   
        ];

        return $this->render("test/associatif.html.twig", [
            "personne"=>$personne,
        ]);
    }

    #[Route("test/objet", name:"app_test_objet")]
    public function objet()
    {
        $personne=new stdClass;
        $personne->nom ="DIALLO";
        $personne->prenom ="Amadou";
        $personne->age =54;
        return $this->render("/test/associatif.html.twig", [
            "personne"=>$personne
        ]);
    }

    #[Route("test/operation",name:"app_test_operation")]
    public function operation(Request $request)
    {
        /* dd($request);*/
        if ($request->isMethod("POST")) {
            $nbre1=$request->get("nbre1");
            $nbre2=$request->get("nbre2");
        }else{
            $nbre1=0;
            $nbre2=0;
        }
        return $this->render("/test/formulaire_numerique.html.twig",[
            "nbre1"=>$nbre1,
            "nbre2"=>$nbre2
        ]);
    }

    #[Route("test/operationbyget",name:"app_test_operationbyget")]
    public function operationByGet(Request $request)
    {
        // dd($request);
        if ($request->isMethod("GET")) {
            $nbre1=$request->get("nbre1");
            $nbre2=$request->get("nbre2");
        }else{
            $nbre1=0;
            $nbre2=0;
        }
        return $this->render("/test/formulaire_numerique_get.html.twig",[
            "nbre1"=>$nbre1,
            "nbre2"=>$nbre2
        ]);
    }


}