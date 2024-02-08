<?php

namespace App\Repository;

use Carbon\Carbon;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\ReadingEntry;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function countBooksByUser(User $user): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getTotalReadsByMonth(User $user)
    {
        $startDate = new \DateTime('first day of this month 00:00:00');
        $endDate = new \DateTime('last day of this month 23:59:59');

        $qb = $this->createQueryBuilder('b');
        $qb->select('SUM(b.totalRead)')
        ->where('b.user = :userId')
        ->andWhere('b.createdAt >= :startDate')
        ->andWhere('b.createdAt <= :endDate')
        ->setParameter('userId', $user->getId())
        ->setParameter('startDate', $startDate)
        ->setParameter('endDate', $endDate);

        $result = $qb->getQuery()->getSingleScalarResult();
        return $result ? (int)$result : 0;
    }

    public function getTotalReadsByYear(User $user)
    {
        $startDate = new \DateTime('first day of January this year 00:00:00');
        $endDate = new \DateTime('last day of December this year 23:59:59');

        $qb = $this->createQueryBuilder('b');
        $qb->select('SUM(b.totalRead) as totalRead')
        ->where('b.user = :userId')
        ->andWhere('b.createdAt >= :startDate')
        ->andWhere('b.createdAt <= :endDate')
        ->setParameter('userId', $user->getId()) 
        ->setParameter('startDate', $startDate)
        ->setParameter('endDate', $endDate);

        $result = $qb->getQuery()->getSingleScalarResult();

        return $result ? (int)$result : 0;
    }


    public function findDayOfLastEntryForMostRecentBook(User $user)
    {
        $recentBook = $this->createQueryBuilder('b')
            ->where('b.user = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('b.updatedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$recentBook) {
            return null;
        }

        $lastEntry = $this->_em->getRepository(ReadingEntry::class)->createQueryBuilder('re')
            ->where('re.book = :bookId')
            ->setParameter('bookId', $recentBook->getId())
            ->orderBy('re.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$lastEntry) {
            return null;
        }
        $day = Carbon::parse($lastEntry->getCreatedAt())->format("l");

        return $day;
    }


    

    
    

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
    // $qb = $this->createQueryBuilder('b')
    //         ->innerJoin('b.readingEntries', 're')
    //         ->where('b.user = :user')
    //         ->andWhere('re.status = :status') 
    //         ->setParameter('user', $user)
    //         ->setParameter('status', $status) 
            // ->getQuery();
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
