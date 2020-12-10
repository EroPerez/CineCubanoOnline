<?php
declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Province;


/**
 * Description of ProvinceRepository
 *
 * @author Michel
 */
class ProvinceRepository extends ServiceEntityRepository {


    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Province::class);
        
    }

}