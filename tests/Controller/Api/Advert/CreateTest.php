<?php

namespace App\Tests\Controller\Api\Advert;

use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\User;
use App\Enum\AdvertStatus;
use App\Enum\UserRole;
use App\Enum\UserStatus;
use App\Tests\AbstractWebTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Exception;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateTest extends AbstractWebTest
{
    private readonly ServiceEntityRepository $cityRepository;
    private readonly ServiceEntityRepository $categoryRepository;
    private readonly ServiceEntityRepository $advertRepository;
    private readonly ServiceEntityRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cityRepository = $this->em->getRepository(City::class);
        $this->categoryRepository = $this->em->getRepository(Category::class);
        $this->advertRepository = $this->em->getRepository(Advert::class);
        $this->userRepository = $this->em->getRepository(User::class);
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testCreateNew201(): void
    {
        $city = $this->cityRepository->findOneBy([]);
        $category = $this->categoryRepository->findOneBy([]);
        $user = $this->userRepository->findOneBy(['status' => UserStatus::Active]);

        $payload = [
            'name'        => $this->faker->words(asText: true),
            'cost'        => $this->faker->randomNumber(),
            'description' => $this->faker->words(asText: true),
            'city'        => $city->getSlug(),
            'category'    => $category->getName(),
        ];

        $this->client->loginUser($user)
            ->jsonRequest(
                Request::METHOD_POST,
                $this->generateUrl('api_create_advert'),
                $payload
            );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $expected = array_merge($payload, [
            'city'      => $city->getName(),
            'category'  => $category->getName(),
            'author_id' => $user->getId(),
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertArraySubset(['data' => $expected], $content);

        $newAdvert = $this->advertRepository->find($content['data']['id']);
        $this->assertEquals(AdvertStatus::Draft, $newAdvert->getStatus());
    }

    /**
     * @dataProvider dataProviderNonUserRoles
     */
    public function testCreateByNonUserRole403(UserRole $role): void
    {
        $user = $this->userRepository->findOneBy(['status' => UserStatus::Active]);
        $user->setRoles([$role]);
        $this->em->flush();

        $city = $this->cityRepository->findOneBy([]);
        $category = $this->categoryRepository->findOneBy([]);

        $payload = [
            'name'        => $this->faker->words(asText: true),
            'cost'        => $this->faker->randomNumber(),
            'description' => $this->faker->words(asText: true),
            'city'        => $city->getSlug(),
            'category'    => $category->getName(),
        ];

        $this->client->loginUser($user)
            ->jsonRequest(
                Request::METHOD_POST,
                $this->generateUrl('api_create_advert'),
                $payload
            );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function dataProviderNonUserRoles(): array
    {
        return [
            [UserRole::Admin],
            [UserRole::Moderator],
        ];
    }
}
