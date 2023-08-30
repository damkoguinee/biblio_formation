<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Emprunt;
use App\Entity\Genre;
use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use App\Repository\EmpruntRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(LivreRepository $livreRepository): Response
    {
        
        return $this->render('home/index.html.twig', [
            "livres" => $livreRepository->findAll()
        ]);
    }

    #[Route('/fiche-auteur-{id}',name:"app_home_auteur", requirements:["id"=>"\d+"])]
    public function auteur (Auteur $auteur)
    {
        return $this->render("home/fiche_auteur.html.twig",["auteur"=>$auteur]);
    }

    #[Route('/fiche-livre-{id}',name:"app_home_livre", requirements:["id"=>"\d+"])]
    public function ficheLivre (Livre $livre, LivreRepository $lr, EmpruntRepository $emr)
    {
        $livre=$lr->find($livre);
        $livresDisponibles=$lr->livresDisponibles();
        // $disponibles=in_array($livre, $livresDisponibles);
        // $livre->setDisponible($disponibles);
        $emprunt=$emr->findOneByLivreEmprunt($livre->getId());

        return $this->render("home/fiche_livre.html.twig",[
            "livre"         =>  $livre,
            "nbreEmprunt"   =>  $emprunt[1],
            // "disponible"    =>  $disponibles
        ]);
    }

    #[Route('/fiche-genre', name: 'app_home_genre')]
    public function genre(GenreRepository $genreRepository): Response
    {
        $genres=$genreRepository->findAll();

        return $this->render('home/genre_list.html.twig', [
            'genres' =>$genres,
        ]);
    }

    #[Route('/fiche-genre-{id}',name:"app_home_fiche_genre", requirements:["id"=>"\d+"])]
    public function ficheGenre (Genre $genre, GenreRepository $gr, LivreRepository $lr)
    {     
        $livres=$lr->find($genre);
        dd($livres);
        return $this->render("home/fiche_genre.html.twig",["livre"=>$livres]);
    }

    #[Route('/livres-indisponibles',name:"app_home_livres_indisponibles")]
    public function indisponibles (LivreRepository $lr)
    {     
        return $this->render("home/indisponibles.html.twig",["livres"=>$lr->livresIndisponibles()]);
    }

    #[Route('/livres-disponibles',name:"app_home_livres_disponibles")]
    public function disponibles (LivreRepository $lr)
    {     
        return $this->render("home/disponibles.html.twig",["livres"=>$lr->livresDisponibles()]);
    }
}