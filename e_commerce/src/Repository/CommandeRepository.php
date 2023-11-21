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
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Pour afficher les commandes passées les 10 derniers jours

        // 1 Créer une requête SQL dans la BDD pour matérialiser le mécanisme
        // 2 Adapter cette requête en DQL
        // 3 Créer la fonction lastDays() dans le CommandeController.php
        
        // Pour afficher la liste de toutes les commandes et en premier plan les commandes passées les 10 derniers jours dans la vue index.html.twig

        // 4 Créer le chemin d'accès href="{{path ('app_commande_list')}}"> 

    public function lastDays()
        { 
            /////////////////////SQL//////////////////////                              
            // SELECT c.date_commande
            // FROM commande c
            // WHERE ADDDATE(date_commande, INTERVAL 10 DAY) >= CURDATE()
                
            /////////////////////// DQL////////////////////
                $dateLimit = new \DateTime();                                           // Créer une variable pour initialiser une nouvelle Date en guise de référence
                $dateLimit->modify('-10 days');                                         // Utiliser la Fonction modify() pour modifier cette date
                
                $query = $this->createQueryBuilder('c')                                 // Pour créer la requête

                ->andWhere ('c.date_commande >= :dateLimit')                            // Remplace le WHERE en SQL pour filtrer l'affichage au 20 derniers jours
                ->setParameter (':dateLimit', $dateLimit)                               // Sécurité pour éviter l'injection SQL           

                ->getQuery();                                                           // Récupérer le résultat 
                return $query->getResult()                                              // Renvoyer le résultat de la requête
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
