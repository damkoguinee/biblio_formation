<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** 
            $options["datat] permet de recupurer l'objet qui est utilisé comme données du formulaire. 
        */
        $abonne=$options["data"];
        $builder
            ->add('pseudo')
            ->add('password', null, [
                "mapped"        =>false,
                "required"      => $abonne->getId() ? false : true
            ])
            ->add('roles',ChoiceType::class,[
                "choices"       =>  [
                    "Abonné"            =>"ROLE_USER",
                    "Lecteur"           =>"ROLE_LECTEUR",
                    "Bibliothécaire"    =>"ROLE_BIBLIO",
                    "Administrateur"    =>"ROLE_ADMIN",
                    "Développeur"       =>"ROLE_DEV",
                ],
                "multiple"  =>true,
                "expanded"  =>true,
                "label"     =>"Niveau d'accès"
            ])
            ->add('prenom')
            ->add('nom')
            ->add('naissance', DateType::class, [
                "widget"    =>"single_text",
                "required"  =>"false"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
