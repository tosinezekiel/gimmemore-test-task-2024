<?php 
namespace App\Service;

use App\Entity\Book;
use App\Entity\User;
use App\Utils\Status;
use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Transformers\ReviewTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;



class ReviewService
{
    public function __construct(
        private ReviewRepository $reviewRepository, 
        private EntityManagerInterface $entityManager
    )
    {}

   public function rateBook(Book $book, User $user, $payload)
   {
        if(!$book->belongsTo($user) || $book->getStatus() !== Status::COMPLETED){
            throw new AccessDeniedHttpException("This resource does not belong to this user");
        }

        $review = new Review();

        $review->setRating($payload->rating);
        $review->setBook($book);
        $review->setUser($user);
        $review->setComment($payload->comment);

        $this->entityManager->persist($review);
        $this->entityManager->flush();

        $entries = $this->reviewRepository->findBy(['book' => $book]);

        return ReviewTransformer::transformAll($entries);
   } 
}
