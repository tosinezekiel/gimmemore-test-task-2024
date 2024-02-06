<?php 
namespace App\Service;

use App\Entity\User;
use App\Utils\Status;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Transformers\BookTransformer;
use App\Transformers\UserTransformer;
use Doctrine\ORM\EntityManagerInterface;


class UserService
{
    public function __construct(
        private BookRepository $bookRepository, 
        private UserRepository $userRepository, 
        private EntityManagerInterface $entityManager
    )
    {}
    
    public function getProfile(User $user)
    {
        $completedBooks = $this->bookRepository->findBy(['user' => $user, 'status' => Status::COMPLETED]);

        $inProgressBooks = $this->bookRepository->findBy(['user' => $user, 'status' => Status::IN_PROGRESS]);

        return [
            'completed_books' => BookTransformer::transformAll($completedBooks),
            'in_progress_books' => BookTransformer::transformAll($inProgressBooks),
            'user' => UserTransformer::transform($user)
        ];
    }
}
