<?php 
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

class RegisterRequestDTO
{
    #[Assert\NotBlank(message: "Email should not be blank.")]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
    public string $email;

    #[Assert\NotBlank(message: "Password should not be blank.")]
    #[Assert\Length(
        min: 6,
        max: 50,
        minMessage: "Password must be at least {{ limit }} characters long",
        maxMessage: "Password cannot be longer than {{ limit }} characters"
    )]
    public string $password;

    #[Assert\NotBlank(message: "Password confirmation should not be blank.")]
    #[Assert\EqualTo(propertyPath: 'password', message: "Passwords do not match.")]
    #[SerializedName("password_confirmation")]
    public string $passwordConfirmation;

    #[Assert\NotBlank(message: "First name should not be blank.")]
    #[SerializedName("first_name")]
    public string $firstName;

    #[SerializedName("last_name")]
    #[Assert\NotBlank(message: "Last name should not be blank.")]
    public string $lastName;
}
