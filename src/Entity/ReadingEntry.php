<?php

namespace App\Entity;

use App\Traits\Timestamps;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'reading_entries', indexes: [new Index(name: "idx_pages_read", columns: ["pages_read"])])]
class ReadingEntry
{
    use Timestamps;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'readingEntries')]
    #[ORM\JoinColumn(nullable: false, name: "book_id", referencedColumnName: "id")]
    private Book $book;

    #[ORM\Column(type: Types::INTEGER)]
    private int $pagesRead;

    public function __construct()
    {
        // Initialize properties if needed
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function setBook(Book $book): self
    {
        $this->book = $book;
        return $this;
    }

    public function getPagesRead(): int
    {
        return $this->pagesRead;
    }

    public function setPagesRead(int $pagesRead): self
    {
        $this->pagesRead = $pagesRead;
        return $this;
    }
}
