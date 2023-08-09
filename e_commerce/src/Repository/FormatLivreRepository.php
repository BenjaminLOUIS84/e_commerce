<?php

namespace App\Repository;

use App\Entity\FormatLivre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FormatLivre>
 *
 * @method FormatLivre|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormatLivre|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormatLivre[]    findAll()
 * @method FormatLivre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormatLivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormatLivre::class);
    }

//    /**
//     * @return FormatLivre[] Returns an array of FormatLivre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FormatLivre
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
