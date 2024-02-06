<?php 
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ReviewRequestDTO
{
    #[Assert\NotBlank(message: "Rating should not be blank.")]
    #[Assert\Range(min: 1, max: 5, notInRangeMessage: "Rating must be between 1 and 5.")]
    public int $rating;

    #[Assert\NotBlank(message: "Comment should not be blank.")]
    #[Assert\Type(type: "string", message: "Comment must be a valid text.")]
    public string $comment;
}
