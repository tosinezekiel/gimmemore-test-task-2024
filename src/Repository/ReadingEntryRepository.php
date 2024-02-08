<?php

namespace App\Repository;

use DateTime;
use App\Entity\User;
use App\Entity\ReadingEntry;
use Doctrine\ORM\Query\Expr\Join;
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

    public function findMonthlyReadingStatsForCurrentUser(User $user)
    {
        $em = $this->getEntityManager();
        $currentMonth = (new \DateTime())->format('m');
        $currentYear = (new \DateTime())->format('Y');
    
        $qb = $em->createQueryBuilder();
        $qb->select('SUM(re.pagesRead) as totalPages')
           ->from('App\Entity\ReadingEntry', 're')
           ->join('App\Entity\Book', 'b', \Doctrine\ORM\Query\Expr\Join::WITH, 're.book = b.id')
           ->where('b.user = :userId')
           ->andWhere($qb->expr()->andX(
               $qb->expr()->eq($qb->expr()->substring('re.createdAt', 1, 4), ':year'),
               $qb->expr()->eq($qb->expr()->substring('re.createdAt', 6, 2), ':month')
           ))
           ->setParameter('userId', $user->getId())
           ->setParameter('year', $currentYear)
           ->setParameter('month', $currentMonth);
    
        $result = $qb->getQuery()->getSingleScalarResult();
    
        return (int)$result; 
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
