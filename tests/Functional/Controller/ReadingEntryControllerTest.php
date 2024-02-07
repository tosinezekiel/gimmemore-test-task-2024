<?php 
namespace App\Tests\Controller;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Zenstruck\Foundry\Test\ResetDatabase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ReadingEntryControllerTest extends WebTestCase
{
    use ResetDatabase;

    private $entityManager;
    private $encoder;
    private $faker;

    public function setUp(): void
    {
        parent::setUp();
        $client = static::createClient();
        $container = $client->getContainer();
        $this->faker = Factory::create();

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->encoder = $container->get(UserPasswordHasherInterface::class);
    }

    public function testUpdateReadingActivity(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $user = $this->createUserAndLogIn($client);

        $book = $this->createBookForUser($user);

        $readingEntryData = [
            'pagesRead' => 20
        ];

        $client->request('PATCH', '/api/auth/books/'.$book->getId().'/read', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($readingEntryData));

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertEquals('Reading activity updated successfully.', $response['message']);
    }

    private function createUserAndLogIn($client): User
    {
        $user = $this->guestUser(); 
        $client->loginUser($user);
        return $user;
    }

    private function createBookForUser(User $user): Book
    {
        $book = new Book();
        $book->setTitle('Example Book')
             ->setAuthor('Author Name')
             ->setGenre('Fiction')
             ->setPageCount(100)
             ->setUser($user);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $book;
    }

    private function createUser($email, $password): User
    {
        $user = new User($email, $password);
        $user->setFirstName($this->faker->firstName());
        $user->setLastName($this->faker->lastName());
    
        return $user;
    }

    private function guestUser(): User
    {
        $email = $this->faker->email();
        $password = 'password';
        $user = $this->createUser($email, $password);
        $encodedPassword = $this->encoder->hashPassword($user, $password);
        $user->setPassword($encodedPassword);

        return $user;
    }
}
