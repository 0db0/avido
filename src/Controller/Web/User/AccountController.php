<?php

declare(strict_types=1);

namespace App\Controller\Web\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route("/account", name: "account", methods: ["GET"])]
    public function account(): Response
    {
        return $this->render('account/user.html.twig');
    }
}