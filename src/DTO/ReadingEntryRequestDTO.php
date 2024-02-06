<?php 
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
use App\Validators\BookCompleted;

class ReadingEntryRequestDTO
{

    #[Assert\NotBlank(message: "Page count should not be blank.")]
    #[Assert\Type(type: "integer", message: "Page count '{{ value }}' must be a valid integer.")]
    #[BookCompleted]
    #[SerializedName("pages_read")]
    public int $pagesRead;
}
