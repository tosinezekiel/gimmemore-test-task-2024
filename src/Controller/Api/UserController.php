<?php

namespace App\Controller\Api;

use App\Service\UserService;
use App\Traits\JsonResponsable;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController {
    use JsonResponsable;

    public function __construct(private UserService $userService, private SerializerInterface $serializer, private ValidatorInterface $validator)
    {}

    #[Route('/api/auth/profile', methods: ['GET'])]
    public function profile()
    {
        $data = $this->userService->getProfile($this->getUser());

        return $this->jsonSuccessResponse('Profile retrieved successfully.', $data);
    }
}