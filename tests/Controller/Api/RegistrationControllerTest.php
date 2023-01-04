<?php

namespace App\Tests\Controller\Api;

use App\Entity\User;
use App\Tests\AbstractWebTest;
use Exception;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends AbstractWebTest
{
    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testRegisterNewUser(): void
    {
        $password = $this->faker->password();
        $payload = [
            'first_name' => $this->faker->firstName(),
            'last_name'  => $this->faker->lastName(),
            'email'      => $this->faker->email(),

        ];

        $this->client->jsonRequest(
            Request::METHOD_POST,
            $this->generateUrl('api_register'),
            array_merge($payload, [
                'password'          => $password,
                'repeated_password' => $password,
            ])
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertArraySubset(['data' => $payload], $content);

        $newUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $payload['email']]);
        $this->assertArraySubset($payload, $newUser->toArray());
    }
}
