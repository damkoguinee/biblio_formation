<?php

namespace App\Repository;

use App\Entity\Emprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Emprunt>
 *
 * @method Emprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunt[]    findAll()
 * @method Emprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }

    

   /**
    * @return Emprunt[] Returns an array of Emprunt objects
        SELECT e.* FROM emprunt e WHERE e.date_emprunt IS NULL
    */
   public function findByDateRetourNull(): array
   {
       return $this->createQueryBuilder('e')
           ->andWhere('e.dateRetour IS NULL')
           ->orderBy('e.dateRetour', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

   public function findOneByLivreEmprunt($value)
   {
       return $this->createQueryBuilder('e')
           ->andWhere('e.livre = :val')
           ->setParameter('val', $value)
           ->select("COUNT(e.id)")
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }

   public function findEmpruntByAbonne($value)
   {
       return $this->createQueryBuilder('e')
           ->andWhere('e.abonne = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getResult()
       ;
   }
}
