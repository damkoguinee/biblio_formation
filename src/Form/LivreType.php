<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Genre;
use App\Entity\Livre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                "constraints"   =>[
                    NEW Length([
                        "max"       =>  50,
                        "maxMessage"=>  "Le titre ne doit pas depasser 50 cararcteres"
                    ]),
                    new NotBlank(["message"=>"le titre ne peut pas etre vide"])
                ]
            ])
            ->add('resume')
            ->add('auteur',EntityType::class,[
                "class"         =>  Auteur::class,
                "choice_label"  =>  function(Auteur $a){
                    return $a->getPrenom()." ".strtoupper($a->getNom());
                },
                "placeholder"   =>  "Choisir parmi les auteurs enregistrés..."
            ])

            ->add('genres',EntityType::class,[
                "class"         =>  Genre::class,
                "choice_label"  =>  "libelle",
                "multiple"      =>  true,
                "expanded"      =>  true
            ])

            ->add("couverture", FileType::class, [
                "mapped"        =>  false,
                "required"      => false,
                "constraints"   => [
                    new File([
                        "mimeTypes" => [ "image/jpeg", "image/gif", "image/png" ],
                        "mimeTypesMessage" => "Formats acceptés : gif, jpg, png",
                        "maxSize" => "2048k",
                        "maxSizeMessage" => "Taille maximale du fichier : 2 Mo"
                    ])
                ],
                "help" => "Formats autorisés : images jpg, png ou gif"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
