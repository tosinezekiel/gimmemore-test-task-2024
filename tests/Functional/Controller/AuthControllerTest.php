<?php 
namespace App\Tests\Controller;

use Faker\Factory;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Zenstruck\Foundry\Test\ResetDatabase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthControllerTest extends WebTestCase
{
    use ResetDatabase;

    private $faker;
    private UserPasswordHasherInterface $encoder;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->encoder = static::getContainer()->get(UserPasswordHasherInterface::class);
    }

    public function testRegister(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]));

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
    }

    public function testLogin(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $email = $this->faker->email();
        $password = 'password';
        $user = $this->createUser($email, $password);
        $encodedPassword = $this->encoder->hashPassword($user, $password);
        $user->setPassword($encodedPassword);

        $client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => $user->getEmail(),
            'password' => $password
        ]));

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
    }

    private function createUser($email, $password): User
    {
        $user = new User($email, $password);
        $user->setFirstName($this->faker->firstName());
        $user->setLastName($this->faker->lastName());

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($user);
        $entityManager->flush();
    
        return $user;
    }
}
