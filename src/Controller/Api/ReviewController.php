<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\DTO\ReviewRequestDTO;
use App\Service\ReviewService;
use App\Traits\JsonResponsable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class ReviewController extends AbstractController {
    use JsonResponsable;

    public function __construct(private ReviewService $reviewService, private SerializerInterface $serializer, private ValidatorInterface $validator)
    {}

    #[Route('/api/auth/books/{book}/review', methods: ['POST'])]
    #[ParamConverter('book', class: Book::class)]
    public function update(Book $book, Request $request)
    {
        $dto = $this->serializer->deserialize($request->getContent(), ReviewRequestDTO::class, 'json');

        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            return $this->jsonFormErrorResponse($violations);
        }

        $data = $this->reviewService->rateBook($book, $this->getUser(), $dto);

        return $this->jsonSuccessResponse('Review added successfully.', $data);
    }
}