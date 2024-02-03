<?php 
namespace App\Service;

use Exception;
use App\Entity\User;
use App\DTO\RegisterRequestDTO;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthService
{
    private const INVALID_CREDENTIALS = 'email:%s and password provided do not match any record.';

    public function __construct(
        private UserRepository $userRepository, 
        private UserPasswordHasherInterface $encoder, 
        private JWTTokenManagerInterface $JWTManager,
        private EntityManagerInterface $entityManager
    )
    {}

    public function registerAndSign(RegisterRequestDTO $dto): ?string
    {
        $plainTextPassword = $dto->password;
        $user = new User($dto->email, $dto->password);

        $user->setEmail($dto->email);
        $user->setFirstName($dto->firstName);
        $user->setLastName($dto->lastName);
        $encodedPassword = $this->encoder->hashPassword($user, $plainTextPassword);
        $user->setPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->JWTManager->create($user);
    }

    public function verifyAndSign(string $email, string $password): ?string
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (!$user || !$this->encoder->isPasswordValid($user, $password)) {
            throw new Exception(sprintf(self::INVALID_CREDENTIALS, $email));
        }

        return $this->JWTManager->create($user);
    }
}
