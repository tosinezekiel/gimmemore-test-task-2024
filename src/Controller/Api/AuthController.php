<?php

namespace App\Controller\Api;

use App\DTO\AuthRequestDTO;
use App\Service\AuthService;
use App\Service\UserService;
use App\DTO\RegisterRequestDTO;
use App\Traits\JsonResponsable;
use App\Transformers\UserTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AuthController extends AbstractController {
    use JsonResponsable;

    public function __construct(private AuthService $authService, private SerializerInterface $serializer, private ValidatorInterface $validator, private UserService $userService)
    {}

    #[Route('/api/register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        try{

            $dto = $this->serializer->deserialize($request->getContent(), RegisterRequestDTO::class, 'json');

            $violations = $this->validator->validate($dto);

            if (count($violations) > 0) {
                return $this->jsonFormErrorResponse($violations);
            }

            $token = $this->authService->registerAndSign($dto);

            $user = UserTransformer::transform($this->userService->getUserByEmail($dto->email));

            return $this->jsonSuccessResponse('User registered successfully', ['token' => $token, 'user' => $user]);
        } catch (UniqueConstraintViolationException $e) {
            return $this->jsonErrorResponse('This email address is already registered', [], Response::HTTP_CONFLICT);
        }
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