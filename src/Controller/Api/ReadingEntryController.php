<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\DTO\BookRequestDTO;
use App\DTO\ReadingEntryRequestDTO;
use App\Traits\JsonResponsable;
use App\Service\ReadingEntryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class ReadingEntryController extends AbstractController {
    use JsonResponsable;

    public function __construct(private ReadingEntryService $readingService, private SerializerInterface $serializer, private ValidatorInterface $validator)
    {}

    #[Route('/api/auth/books/{book}/read', methods: ['PATCH'])]
    #[ParamConverter('book', class: Book::class)]
    public function update(Book $book, Request $request)
    {
        $dto = $this->serializer->deserialize($request->getContent(), ReadingEntryRequestDTO::class, 'json');

        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            return $this->jsonFormErrorResponse($violations);
        }

        $data = $this->readingService->addReadingActivity($this->getUser(), $book, $dto);


        return $this->jsonSuccessResponse('Reading activity updated successfully.', $data);
    }
}