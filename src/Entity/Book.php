<?php

namespace App\Entity;

use App\Traits\Timestamps;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use App\Utils\Status;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Table(name: 'books', indexes: [new Index(name: "idx_title", columns: ["title"]), new Index(name: "idx_author", columns: ["author"]), new Index(name: "idx_slug", columns: ["slug"])])]
class Book
{
    use Timestamps;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private string $title;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $author;

    #[Gedmo\Slug(fields: ['title'])]
    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private string $slug;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $genre;

    #[ORM\Column(type: Types::INTEGER)]
    private int $pageCount;

    #[ORM\Column(type: Types::INTEGER)]
    private int $totalRead = 0;

    #[ORM\Column(type: Types::STRING)]
    private string $status = Status::FRESH;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'readingEntries')]
    #[ORM\JoinColumn(nullable: false, name: "user_id", referencedColumnName: "id")]
    private User $user;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: ReadingEntry::class)]
    private Collection $readingEntries;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Review::class)]
    private Collection $reviews;

    public function __construct()
    {
        $this->readingEntries = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;

    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;
        return $this;

    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;
        return $this;

    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setPageCount(int $pageCount): self
    {
        $this->pageCount = $pageCount;
        return $this;

    }

    public function getPageCount(): ?int
    {
        return $this->pageCount;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getTotalRead(): ?int
    {
        return $this->totalRead;
    }

    public function setTotalRead(int $pagesRead): self
    {
        $this->totalRead += $pagesRead;
        return $this;
    }

    public function belongsTo(User $user): bool
    {
        return $this->getUser()->getId() === $user->getId();
    }
}
