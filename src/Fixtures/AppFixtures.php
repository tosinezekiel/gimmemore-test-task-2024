<?php
namespace App\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Book;
use App\Entity\Review;
use App\Entity\ReadingEntry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User($faker->email, 'password');
            $user->setEmail($faker->email)
                 ->setFirstName($faker->firstName)
                 ->setLastName($faker->lastName)
                 ->setPassword($this->passwordHasher->hashPassword($user, 'password'));

            $manager->persist($user);
            $users[] = $user;
        }

        $books = [];
        for ($i = 0; $i < 20; $i++) {
            $book = new Book();
            $book->setTitle($faker->sentence)
                 ->setAuthor($faker->name)
                 ->setGenre($faker->word)
                 ->setPageCount($faker->numberBetween(100, 500));

            $manager->persist($book);
            $books[] = $book;
        }

        foreach ($users as $index => $user) {
            if ($index < 5) {
                for ($j = 0; $j < 5; $j++) {
                    $readingEntry = new ReadingEntry();
                    $readingEntry->setUser($user)
                                 ->setBook($books[array_rand($books)])
                                 ->setPagesRead($faker->numberBetween(1, 500));

                    $manager->persist($readingEntry);

                    $review = new Review();
                    $review->setUser($user)
                           ->setBook($books[array_rand($books)])
                           ->setRating($faker->numberBetween(1, 5))
                           ->setComment($faker->paragraph);

                    $manager->persist($review);
                }
            }
        }

        $manager->flush();
    }
}
