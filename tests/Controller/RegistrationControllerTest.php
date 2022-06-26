<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTest;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends AbstractWebTest
{
    /**
     * @throws JsonException
     */
    public function testRegisterNewUser(): void
    {
        $payload = [
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
            'password'   => 'Secret_Password12@',
        ];

        $this->client->jsonRequest(
            Request::METHOD_POST,
            $this->generateUrl('api_register', $payload)
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertArraySubset(['data' => $payload], $content);
    }
}