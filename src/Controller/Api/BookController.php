<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\DTO\BookRequestDTO;
use App\Service\BookService;
use App\Traits\JsonResponsable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class BookController extends AbstractController {
    use JsonResponsable;

    public function __construct(private BookService $bookService, private SerializerInterface $serializer, private ValidatorInterface $validator)
    {}

    #[Route('/api/auth/books', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {   
        $data = $this->bookService->getBooks($this->getUser());

        return $this->jsonSuccessResponse('Books retrieved successfully.', $data);
    }

    #[Route('/api/auth/books', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), BookRequestDTO::class, 'json');

        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            return $this->jsonFormErrorResponse($violations);
        }

        $data = $this->bookService->save($this->getUser(), $dto);

        return $this->jsonSuccessResponse('Book added successfully.', $data);
    }

    #[Route('/api/auth/books/{book}', methods: ['GET'])]
    #[ParamConverter('book', class: Book::class)]
    public function show(Book $book): JsonResponse
    {
        $data = $this->bookService->getBook($this->getUser(), $book);
        return $this->jsonSuccessResponse('Book retrieved successfully.', $data);
    }
}