<?php

namespace App\Tests\Controller\Api\Advert;

use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\User;
use App\Enum\AdvertStatus;
use App\Enum\UserStatus;
use App\Tests\AbstractWebTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Exception;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateTest extends AbstractWebTest
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
     * @dataProvider dataProviderValidUpdateStatus
     *
     * @throws JsonException
     * @throws Exception
     */
    public function testUpdateOk(AdvertStatus $status): void
    {
        $city = $this->cityRepository->findOneBy([]);
        $category = $this->categoryRepository->findOneBy([]);
        $user = $this->userRepository->findOneBy(['status' => UserStatus::Active]);
        $advert = $this->advertRepository->findOneBy(['author' => $user, 'status' => $status]);

        $payload = [
            'name'        => $this->faker->words(asText: true),
            'cost'        => $this->faker->randomNumber(),
            'description' => $this->faker->words(asText: true),
            'city'        => $city->getSlug(),
            'category'    => $category->getName(),
        ];

        $this->client->loginUser($user)
            ->jsonRequest(
                Request::METHOD_PUT,
                $this->generateUrl('api_edit_advert', ['id' => $advert->getId()]),
                $payload
            );

        $response = $this->client->getResponse();

        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $expected = array_merge($payload, [
            'city'      => $city->getName(),
            'category'  => $category->getName(),
            'author_id' => $user->getId(),
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertArraySubset(['data' => $expected], $content);
    }

    /**
     * @dataProvider dataProviderInValidUpdateStatus
     */
    public function testUpdateWhenAdvertInInvalidStatus403(AdvertStatus $status): void
    {
        $advert = $this->advertRepository->findOneBy(['status' => $status]);

        $payload = [
            'name'        => $this->faker->words(asText: true),
            'cost'        => $this->faker->randomNumber(),
            'description' => $this->faker->words(asText: true),
        ];

        $this->client->loginUser($advert->getAuthor())
            ->jsonRequest(
                Request::METHOD_PUT,
                $this->generateUrl('api_edit_advert', ['id' => $advert->getId()]),
                $payload
            );


        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testUpdateNonAdvertOwner403(): void
    {
        $advert = $this->advertRepository->findOneBy(['status' => AdvertStatus::Draft]);
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->neq('id', $advert->getAuthor()->getId()));
        $nonOwner = $this->userRepository->matching($criteria)->first();

        $payload = [
            'name'        => $this->faker->words(asText: true),
            'cost'        => $this->faker->randomNumber(),
            'description' => $this->faker->words(asText: true),
        ];

        $this->client->loginUser($nonOwner)
            ->jsonRequest(
                Request::METHOD_PUT,
                $this->generateUrl('api_edit_advert', ['id' => $advert->getId()]),
                $payload
            );


        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function dataProviderValidUpdateStatus(): array
    {
        return [
            [AdvertStatus::Draft],
            [AdvertStatus::Rejected],
        ];
    }

    public function dataProviderInValidUpdateStatus(): array
    {
        return [
            [AdvertStatus::Active],
            [AdvertStatus::Moderation],
        ];
    }
}
