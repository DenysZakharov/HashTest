<?php

namespace App\Repository;

use App\Entity\Hash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hash>
 *
 * @method Hash|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hash|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hash[]    findAll()
 * @method Hash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HashRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hash::class);
    }

    /**
     * @return \ArrayIterator<int, Hash>
     */
    public function findByHashCode(string $hashCode): iterable
    {
        $qb = $this->createQueryBuilder('h')
            ->andWhere('h.hashcode = :val')
            ->setParameter('val', $hashCode);

        return $qb->getQuery()->toIterable();
    }
}
