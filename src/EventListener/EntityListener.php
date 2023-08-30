<?php 
namespace App\EventListener;

use App\Entity\Livre;
use Doctrine\ORM\Event\LifecycleEventArgs;


class EntityListener {
    /* cette méthode est déclenché pour chaque évènement lié à une entité */

    //public function onEntityLifecycle(LifecycleEventArgs $lifeCycle)

    /**
     * L'evenement 'postLoad' est declenché apres le chargement d'un objet entité à partir de la bdd.
     */
    public function postLoad($entite, LifecycleEventArgs $lifeCycle)
    {
        // on recupère l'entite qui a déclenché l'evenement
        $entite= $lifeCycle->getObject();
        // on vérifie si l'entité est de type livre
        if ($entite instanceof Livre) {
            $livreRepository =$lifeCycle->getObjectManager()->getRepository(Livre::class);
            /**
             * @var \App\Repository\LivreRepository $livreRepository
             * 
             * cette annotation n'est utile que pour VS code. cela permet à VS code de considérer la variable $livreRepository comme un objet de la classe  \App\Repository\LivreRepository
             */
            $entite->setDisponible(in_array($entite, $livreRepository->livresDisponibles()));
        }
    }
}