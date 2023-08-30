<?php

namespace App\Repository;

use App\Entity\Emprunt;
use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 *
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

   /**
    * @return Livre[] Returns le slivres dont le titre contient le mot recherché
    */
   public function findByRecherche($value): array
   {
       return $this->createQueryBuilder('l')
           ->Where('l.titre LIKE :val')
           ->Where('l.resume LIKE :val')
           ->setParameter('val', "%$value%")
           ->orderBy('l.titre', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   /**
    * @return livre[] return les livres indsponibles (qui n'ont pas été rendu)
        SELECT l.*
        FROM emprunt e JOIN livre l ON e.livre_id = l.id
        WHERE e.date_retour IS NULL
    */
    public function livresIndisponibles(): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->join(Emprunt::class,"e","WITH","l.id = e.livre")
            ->andWhere('e.dateRetour IS NULL')
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult()
       ;
    }

    /**
    * @return livre[] return les livres disponibles
        SELECT l.*
        FROM livre l 
        WHERE l.id NOT IN (
            SELECT l.id
            FROM emprunt e JOIN livre l ON e.livre_id = l.id
        WHERE e.date_retour IS NULL )
    */

    public function livresDisponibles(): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT l
            FROM App\Entity\Livre l 
            WHERE l.id NOT IN (
                SELECT liv.id
                FROM App\Entity\Emprunt e 
                JOIN App\Entity\Livre liv WITH e.livre = liv.id
                WHERE e.dateRetour IS NULL )
            ORDER BY l.titre"
        );
        return $query->getResult();
        
    }
   

//    public function findOneBySomeField($value): ?Livre
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
