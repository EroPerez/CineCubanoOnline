<?php
declare(strict_types=1);

namespace App\Component\Province\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Component\Province\Model\Province;

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