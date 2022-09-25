<?php

namespace App\Tests\Controller\Api;

use App\Tests\AbstractWebTest;
use Exception;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertTest extends AbstractWebTest
{
    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testCreateNew201(): void
    {
        $payload = [
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ];

        $this->client->jsonRequest(
            Request::METHOD_POST,
            $this->generateUrl('api_register', array_merge($payload, [
                'password'   => 'Secret_Password12@',
            ]))
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertArraySubset(['data' => $payload], $content);
    }
}
