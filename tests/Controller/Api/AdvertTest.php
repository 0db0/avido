<?php

namespace App\Tests\Controller\Api;

use App\Enum\UserStatus;
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
            'name'        => $this->faker->words(asText: true),
            'cost'        => $this->faker->randomNumber(),
            'description' => $this->faker->words(asText: true),
            'city'        => 'saratov',
            'category'    => 'auto',
        ];

        $this->client->jsonRequest(
            Request::METHOD_POST,
            $this->generateUrl('api_create_advert'),
            $payload
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertArraySubset(['data' => $payload], $content);
    }
}
