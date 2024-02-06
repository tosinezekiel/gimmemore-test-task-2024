<?php

namespace App\Repository;

use App\Entity\ReadingEntry;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<ReadingEntry>
 *
 * @method ReadingEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReadingEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReadingEntry[]    findAll()
 * @method ReadingEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReadingEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReadingEntry::class);
    }

//    /**
//     * @return ReadingEntry[] Returns an array of ReadingEntry objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReadingEntry
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
