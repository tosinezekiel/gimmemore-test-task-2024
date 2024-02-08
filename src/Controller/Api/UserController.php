<?php

namespace App\Controller\Api;

use App\Entity\ReadingEntry;
use App\Service\BookService;
use App\Service\UserService;
use App\Traits\JsonResponsable;
use App\Service\ReadingEntryService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController {
    use JsonResponsable;

    public function __construct(
        private UserService $userService, 
        private SerializerInterface $serializer, 
        private ValidatorInterface $validator, 
        private ReadingEntryService $readingEntryService,
        private BookService $bookService
    )
    {}

    #[Route('/api/auth/dashboard', methods: ['GET'])]
    public function dashboard()
    {
        $data = [];
        $data['latest'] = $this->bookService->getLatestBooks($this->getUser());
        $data['total_books'] = $this->bookService->getUserTotalBooks($this->getUser());
        $data['total_pages_read_in_month'] = $this->bookService->getUserTotalReadInMonth($this->getUser());
        $data['last_seen'] = $this->bookService->getUserLastReadDay($this->getUser());

        return $this->jsonSuccessResponse('Stats retrieved successfully.', $data);
    }

    #[Route('/api/auth/profile', methods: ['GET'])]
    public function profile()
    {
        $data = $this->userService->getProfile($this->getUser());

        return $this->jsonSuccessResponse('Profile retrieved successfully.', $data);
    }
}