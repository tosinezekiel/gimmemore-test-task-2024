<?php
namespace App\Transformers;

use App\Entity\Book;
use Carbon\Carbon;

class BookTransformer {
    public static function transform(?Book $book): array
    {
        if(null){
            return null;
        }
        
        return [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'genre' => $book->getGenre(),
            'author' => $book->getAuthor(),
            'created_at' => Carbon::parse($book->getCreatedAt())->format("F j, Y"),
            'page_count' => $book->getPageCount(),
            'user' => UserTransformer::transform($book->getUser()),
            'slug' => $book->getSlug(),
            'status' => $book->getStatus() ? $book->getStatus() : 'in-progress',
            'read_times' => count($book->getReadingEntries()),
            'total_read' => $book->getTotalRead(),
            'pages_left' => (int) $book->getPageCount() - $book->getTotalRead(),
            'reviews' => ReviewTransformer::transformAll($book->getReviews()->toArray()),
        ];
    }

    public static function transformAll(?array $books): array
    {
        if(null){
            return null;
        }
        return array_map(function($item) {
            return self::transform($item);
        }, $books);
    }
}