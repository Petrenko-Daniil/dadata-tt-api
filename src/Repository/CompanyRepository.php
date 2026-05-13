<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function findOneByInn(string $inn): ?Company
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.inn = :inn')
            ->setParameter('inn', $inn)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
