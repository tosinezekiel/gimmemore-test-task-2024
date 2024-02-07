<?php 
namespace App\Tests\Controller;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Zenstruck\Foundry\Test\ResetDatabase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BookControllerTest extends WebTestCase
{
    use ResetDatabase;

    private $faker;
    private $encoder;
    private $entityManager;


    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->encoder = static::getContainer()->get(UserPasswordHasherInterface::class);
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testIndex(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->logInUser($this->guestUser());

        $client->request('GET', '/api/auth/books');

        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());
        $this->assertEquals('Books retrieved successfully.', json_decode($response->getContent(), true)['message']);
    }

    public function testStoreSuccess(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $user = $this->guestUser();

        $client->logInUser($user);

        $book = [
            'title' => "Simple title",
            'author' => "J. Browns",
            'genre' => "action",
            'page_count' => 12,
        ];

        $client->request('POST', '/api/auth/books', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($book));

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals('Book added successfully.', $response['message']);
    }

    public function testStoreFailValidation(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $user = $this->guestUser();

        $client->logInUser($user);
        $client->request('POST', '/api/auth/books', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');

        $response = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST); 
        $this->assertArrayHasKey('error', $response);
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
