<?php

namespace App\Repository;

use App\Entity\CallStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CallStatus>
 *
 * @method CallStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method CallStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method CallStatus[]    findAll()
 * @method CallStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CallStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CallStatus::class);
    }

    public function save(CallStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CallStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
