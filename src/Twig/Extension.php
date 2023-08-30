<?php
namespace App\Twig;

use App\Entity\Abonne;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

class Extension extends AbstractExtension{
    private $parametres;
    public function __construct(ParameterBagInterface $parametres)
    {
        $this->parametres=$parametres;
    }
    public function extrait(string $texte = null, int $longeueur){
        return strlen($texte) > $longeueur ? substr($texte, 0, $longeueur). "[...]" : $texte;
    }

    public function estNumerique ($variable): bool
    {
        return is_numeric($variable);
    }

    public function roles(Abonne $abonne): string
    {
        $text ="";
        foreach ($$abonne->getRoles() as $role) {
            $text.= $text ? ", " :"";
            switch ($role){
                case 'ROLE_ADMIN':
                    $text .= "Directeur";
                    break;
                
                case 'ROLE_BIBLIO':
                    $text .= "Bibliothécaire";
                    break;
                
                case 'ROLE_LECTEUR':
                    $text .= "Lecteur";
                    break;
                case 'ROLE_USER':
                    $text .= "Abonne";
                    break;
                
                case 'ROLE_DEV':
                    $text .= "Dévéloppeur";
                    break;
                default:
                    $text .="Autre";
                    break;
            }
        }
        return $text;
    }

    public function baliseImg($imageName, $classes = "", $alt ="")
    {
        $balise ="<img src='".$this->parametres->get("chemin_images")."$imageName' class='$classes' alt='$alt'>";
        return $balise;
    }

    /**
     * Pour ajouter une fonction à Twig, on utilise la méthode getFunctions 
     * Pour ajouter un filtre à Twig, on utilise la méthode getFilters 
     * Pour ajouter un test à Twig, on utilise la méthode getTest 
     */

     public function getFunctions()
     {
        return [
            new TwigFunction("extrait", [$this, "extrait"])
        ];
     }

    /* */
    public function getFilters()
    {
        return [
            new TwigFilter("extrait", [$this, "extrait"]),
            new TwigFilter("autorisations", [$this, "roles"]),
            new TwigFilter("img", [$this, "baliseImg"])
        ];
    }

    public function getTests()
    {
        return [
            new TwigTest("numeric", [$this, "estNumerique"])
        ];
    }
}