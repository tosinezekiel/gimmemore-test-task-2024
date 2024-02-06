<?php 
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
use App\Validators\UniqueUserBookTitle;

class BookRequestDTO
{
    #[Assert\NotBlank(message: "Title should not be blank.")]
    #[UniqueUserBookTitle]
    public string $title;

    #[Assert\NotBlank(message: "Author should not be blank.")]
    public string $author;

    #[Assert\NotBlank(message: "Genre should not be blank.")]
    public string $genre;

    #[Assert\NotBlank(message: "Page count should not be blank.")]
    #[Assert\Type(type: "integer", message: "Page count '{{ value }}' must be a valid integer.")]
    #[SerializedName("page_count")]
    public int $pageCount;
}
