<?php
namespace App\Transformers;

use App\Entity\Book;

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
            'created_at' => $book->getCreatedAt(),
            'page_count' => $book->getPageCount(),
            'user' => UserTransformer::transform($book->getUser()),
            'slug' => $book->getSlug(),
            'status' => $book->getStatus(),
            'total_read' => $book->getTotalRead(),
            'pages_left' => (int) $book->getPageCount() - $book->getTotalRead()
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