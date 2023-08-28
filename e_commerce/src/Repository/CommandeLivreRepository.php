<?php

namespace App\Repository;

use App\Entity\CommandeLivre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandeLivre>
 *
 * @method CommandeLivre|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeLivre|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeLivre[]    findAll()
 * @method CommandeLivre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeLivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeLivre::class);
    }

    // public function total(): ?int
    // {
    //     return $this
    //         ->createQueryBuilder('commandeLivre')
    //         ->andWhere('commande_id = :id')
    //         ->setParameter("SUM('quantite' * 'prix_unitaire')")
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    // public function total($commandeLivres)
    // {
    //     $em = $this->getEntityManager();

    //     $requete = $em->createQueryBuilder('cl');

    //     $qB = $requete;

    //     $qB
    //         ->select('SUM(cl.quantite * cl.prix_unitaire)')
    //         ->from('App\Entity\commande_livre')
    //         ->where('cl.commande_id = :id')
    //         ->setParameter('id', $commandeLivres)
    //     ;
            
    //     $query = $requete->getQuery();
    //     return $query->getResult();
        
    // }
    
    //    public function findOneBySomeField($value): ?CommandeLivre
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    //    /**
    //     * @return CommandeLivre[] Returns an array of CommandeLivre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }


}
