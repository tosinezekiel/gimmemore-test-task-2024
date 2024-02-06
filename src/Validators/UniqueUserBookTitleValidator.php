<?php

namespace App\Validators;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security\Core\Security;

class UniqueUserBookTitleValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function validate($value, Constraint $constraint)
    {
        $user = $this->security->getUser();
        $bookRepository = $this->entityManager->getRepository(Book::class);
        $existingBook = $bookRepository->findOneBy(['title' => $value, 'user' => $user]);

        if ($existingBook) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
