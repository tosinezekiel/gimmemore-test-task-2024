<?php
namespace App\Transformers;

use App\Entity\Review;

class ReviewTransformer {
    public static function transform(?Review $review): array
    {
        if(null){
            return null;
        }

        return [
            'id' => $review->getId(),
            'rating' => $review->getRating(),
            'comment' => $review->getComment(),
            'book' => BookTransformer::transform($review->getBook()),
            'user' => UserTransformer::transform($review->getUser()),
            'created_at' => $review->getCreatedAt(),
        ];
    }

    public static function transformAll(?array $reviews): array
    {
        if(null){
            return null;
        }
        return array_map(function($item) {
            return self::transform($item);
        }, $reviews);
    }
}