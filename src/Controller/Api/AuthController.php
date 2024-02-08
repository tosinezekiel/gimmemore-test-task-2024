<?php

namespace App\Controller\Api;

use App\DTO\AuthRequestDTO;
use App\Service\AuthService;
use App\DTO\RegisterRequestDTO;
use App\Service\UserService;
use App\Traits\JsonResponsable;
use App\Transformers\UserTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;


class AuthController extends AbstractController {
    use JsonResponsable;

    public function __construct(private AuthService $authService, private SerializerInterface $serializer, private ValidatorInterface $validator, private UserService $userService)
    {}

    #[Route('/api/register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), RegisterRequestDTO::class, 'json');

        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            return $this->jsonFormErrorResponse($violations);
        }

        $token = $this->authService->registerAndSign($dto);

        $user = UserTransformer::transform($this->userService->getUserByEmail($dto->email));

        return $this->jsonSuccessResponse('User registered successfully', ['token' => $token, 'user' => $user]);
    }

    #[Route('/api/login', methods: ['POST'])]
    public function login(Request $request)
    {
        $dto = $this->serializer->deserialize($request->getContent(), AuthRequestDTO::class, 'json');

        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            return $this->jsonFormErrorResponse($violations);
        }

        $token = $this->authService->verifyAndSign(
            $dto->email, 
            $dto->password
        );

        $user = UserTransformer::transform($this->userService->getUserByEmail($dto->email));

        return $this->jsonSuccessResponse('Login successful', ['token' => $token, 'user' => $user]);
    }

    #[Route('/logout', methods: ['POST'])]
    public function logout()
    {
        
    }
}