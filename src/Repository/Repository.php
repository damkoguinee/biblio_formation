<?php 

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class Repository extends ServiceEntityRepository{
    /**
     on peut surcharger la méthode 'findAll' pour pouvoir choisir l'ordre des resultsts en utilsant la méthode 'findBy'
     */

     public function findAll(array $ordre=[]){
        return $this->findBy([], $ordre);
     }
}