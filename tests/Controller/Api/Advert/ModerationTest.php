<?php

namespace App\Tests\Controller\Api\Advert;

use App\Entity\Advert;
use App\Entity\User;
use App\Enum\AdvertStatus;
use App\Enum\UserStatus;
use App\Tests\AbstractWebTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Exception;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ModerationTest extends AbstractWebTest
{
    private readonly ServiceEntityRepository $advertRepository;
    private readonly ServiceEntityRepository $userRepository;


    protected function setUp(): void
    {
        parent::setUp();
        $this->advertRepository = $this->em->getRepository(Advert::class);
        $this->userRepository = $this->em->getRepository(User::class);
    }


    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testUserPushToModerationOk(): void
    {
        $user = $this->userRepository->findOneBy(['status' => UserStatus::Active]);
        $advert = $this->advertRepository->findOneBy(['author' => $user, 'status' => AdvertStatus::Draft]);

        $this->client->loginUser($user)
            ->jsonRequest(
                Request::METHOD_POST,
                $this->generateUrl('api_push_to_moderation_advert', ['id' => $advert->getId()])
            );

        $response = $this->client->getResponse();

        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $expected = array_merge($advert->toArray(), [
            'status' => AdvertStatus::Moderation->name,
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertArraySubset(['data' => $expected], $content);

        $mail = self::getMailerMessage();
        $expectedAddress = $this->client->getContainer()->getParameter('app.moderation.email');
        self::assertQueuedEmailCount(1);
        self::assertEmailAddressContains($mail, 'To', $expectedAddress);
    }
}
