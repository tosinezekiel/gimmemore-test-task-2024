<?php 
namespace App\Service;

use App\Entity\Book;
use App\Entity\User;
use App\DTO\BookRequestDTO;
use App\Repository\BookRepository;
use App\Transformers\BookTransformer;
use Doctrine\ORM\EntityManagerInterface;

class BookService
{
    public function __construct(
        private BookRepository $bookRepository, 
        private EntityManagerInterface $entityManager
    )
    {}

    public function getBooks(User $user) 
    {
        return BookTransformer::transformAll($this->bookRepository->findBy(['user' => $user]));
    }

    public function getBook(User $user, Book $book) 
    {
        return BookTransformer::transform($this->bookRepository->findOneBy([
            'user' => $user,
            'id' => $book->getId()
        ]));
    }

    public function save(User $user, BookRequestDTO $payload)
    {
        $book = new Book();

        $book->setTitle($payload->title);
        $book->setAuthor($payload->author);
        $book->setGenre($payload->genre);
        $book->setPageCount($payload->pageCount);
        $book->setUser($user);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return BookTransformer::transform($book);
    }

    public function fetchBookByUser(User $user, Book $book)
    {
        $book = $this->bookRepository->findOneBy([
            'user' => $user,
            'id' => $book->getId()
        ]);

        return BookTransformer::transform($book);
    }
}
