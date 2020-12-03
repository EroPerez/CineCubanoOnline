<?php

namespace App\Repository;

use App\Entity\ContactConversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactConversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactConversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactConversation[]    findAll()
 * @method ContactConversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactConversationRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, ContactConversation::class);
    }

    // /**
    //  * @return ContactConversation[] Returns an array of ContactConversation objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('c')
      ->andWhere('c.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('c.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?ContactConversation
      {
      return $this->createQueryBuilder('c')
      ->andWhere('c.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
