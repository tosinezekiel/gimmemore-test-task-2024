<?php
namespace App\Transformers;

use App\Entity\User;

class UserTransformer {
    public static function transform(?User $user): array
    {
        if(null){
            return null;
        }

        return [
            'id' => $user->getId(),
            'first_name' => ucfirst($user->getFirstName()),
            'last_name' => ucfirst($user->getLastName()),
            'full_name' => ucfirst($user->getFirstName()) . ' ' . ucfirst($user->getLastName()),
            'email' => ucfirst($user->getEmail()),
            'joined_at' => $user->getCreatedAt(),
        ];
    }

    public static function transformAll(?array $users): array
    {
        if(null){
            return null;
        }
        return array_map(function($item) {
            return self::transform($item);
        }, $users);
    }
}