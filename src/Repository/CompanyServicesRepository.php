<?php

namespace App\Repository;

use App\Entity\CompanyServices;
use App\Component\Province\Model\Province;
use App\HttpDoctrineFilter\Dto\QueryConfiguration;
use App\HttpDoctrineFilter\Filter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method CompanyServices|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyServices|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyServices[]    findAll()
 * @method CompanyServices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyServicesRepository extends ServiceEntityRepository {

    protected $filter;
    protected $paginator;

    public function __construct(ManagerRegistry $registry, Filter $filter, PaginatorInterface $paginator) {
        parent::__construct($registry, CompanyServices::class);
        $this->filter = $filter;
        $this->paginator = $paginator;
    }

    public function fetch(array $conditions, int $page, int $size): PaginationInterface {
        $qb = $this->filter->createQueryBuilder();
        $qb->select(['service'])
                ->from(CompanyServices::class, 'service')
                ->leftJoin('service.category', 'category', Join::WITH)
                ->leftJoin('service.company', 'company', Join::WITH)
//                ->leftJoin('company.owner', 'owner', Join::WITH)
//                ->leftJoin('company.province', 'province', Join::WITH)
                ;

        return $this->paginator->paginate($this->filter->apply($qb, new QueryConfiguration($conditions)), $page, $size);
    }

    // /**
    //  * @return CompanyServices[] Returns an array of CompanyServices objects
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
      public function findOneBySomeField($value): ?CompanyServices
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
