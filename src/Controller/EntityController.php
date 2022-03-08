<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntityController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserRepository         $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    #[Route("/create", name: "create_action", methods: ["GET"])]
    public function create(): Response
    {
//        $user = new User();
//        $user->setEmail('app4@example.com');
//        $user->setFirstname('John4');
//        $user->setLastname('Doe4');
//        $user->setPhoneNumber('40000');
//        $user->setWhenConvenientReceiveCalls('с 10 до 19');
//
//        $this->em->persist($user);
//        $this->em->flush();

        $user = $this->userRepository->find(9);
        $city = $this->em->getRepository(City::class)->find(1);
        $category = $this->em->getRepository(Category::class)->find(3);

        $advert = new Advert();
        $advert->setName('First advert');
        $advert->setDescription('Foo Bar Description');
        $advert->setCity($city);
        $advert->setCategory($category);
        $advert->setCost(1000);
        $advert->setSeller($user);
        $advert->setStatus(0);

        $this->em->persist($advert);
        $this->em->flush();

        return new JsonResponse(['status' => 'success'], 200);
    }

    #[Route("/update", name: "update_action", methods: ["PUT"])]
    public function update(): Response
    {
        $user = $this->userRepository->find(4);
        $user->setWhenConvenientReceiveCalls('с 10 до 23');

        $this->em->flush();

        return new JsonResponse(['status' => 'updated'], 201);
    }

    #[Route("/show/{id}", name: "show_action", methods: ["GET"])]
    public function show(int $id): Response
    {
        $user = $this->userRepository->find($id);
//        date_default_timezone_set('Europe/Moscow');
//        return new JsonResponse(['date' => date('d-m-Y H:i:s', $user->getUpdatedAt()->getTimestamp())], 200);
        return new JsonResponse(['user' => $user->getId()], 200);
    }
}