<?php 
namespace App\Service;

use App\Entity\Book;
use App\Entity\User;
use App\Utils\Status;
use App\Entity\ReadingEntry;
use App\Service\BookService;
use App\Repository\BookRepository;
use App\DTO\ReadingEntryRequestDTO;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReadingEntryRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ReadingEntryService
{
    public function __construct(
        private ReadingEntryRepository $readingEntryRepository, 
        private BookRepository $bookRepository, 
        private BookService $bookService, 
        private EntityManagerInterface $entityManager
    )
    {}

    public function addReadingActivity(User $user, Book $book, ReadingEntryRequestDTO $payload) 
    {
        if(!$book->belongsTo($user)){
            throw new AccessDeniedHttpException("This resource does not belong to this user");
        }
        
        $entry = new ReadingEntry();

        $entry->setPagesRead($payload->pagesRead);
        $entry->setBook($book);

        $this->entityManager->persist($entry);
        $this->entityManager->flush();

        $entries = $this->readingEntryRepository->findBy(['book' => $book]);
        $totalPagesRead = array_sum(array_map(function($entry) {
            return $entry->getPagesRead();
        }, $entries));

        $book->setTotalRead($entry->getPagesRead());
        $this->entityManager->flush();

        if ($totalPagesRead >= $book->getPageCount()) {
            $book->setStatus(Status::COMPLETED); 
            $this->entityManager->flush();
        }

        return $this->bookService->fetchBookByUser($user, $book);
    }
    
}
