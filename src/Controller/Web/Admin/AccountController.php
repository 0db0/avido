<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route("/account", name: "account", methods: ["GET"])]
    public function account(): Response
    {
        $user = $this->getUser();

        return $this->render('account/admin.html.twig', [
            'first_name' => $user?->getFirstname(),
            'last_name'  => $user?->getLastname(),
        ]);
    }
}