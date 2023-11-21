<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //REQUETES SQL -> DQL
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Pour afficher les commandes passées en novembre

        // 1 Créer une requête SQL dans la BDD pour matérialiser le mécanisme
        // 2 Adapter cette requête en DQL
        // 3 Créer la fonctions showPast() dans le CommandeController.php
        // 4 Créer les chemins d'accès href="{{path ('')}}">Commande passées en Novembre 2023 dans la vue index twig

        // Ci dessous sont des requêtes DQL
    public function novembre($date_commande): ?array
        { 
            /////////////////////DQL//////////////////////       Novembre 2023           /////////////////////// SQL////////////////////

            return $this                                                                 // $entityManager = $this->getEntityManager();
            
                ->createQueryBuilder('commande')                                         // $query = $entityManager->createQuery()

                ->andWhere('commande.date_commande > CURRENT_DATE()')                    // WHERE s.date_commande < CURDATE()

                ->getQuery()                                                             // return $query->getResult();
                ->getResult()
            ;

        }


//    /**
//     * @return Commande[] Returns an array of Commande objects
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

//    public function findOneBySomeField($value): ?Commande
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
