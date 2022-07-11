<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route("/account", name: "account", methods: ["GET"])]
    public function account(Request $request): Response
    {

        $session = $request->getSession();

        dd(unserialize($session->get('_security_main')));
        $user = $this->getUser();

        return $this->render('account/admin.html.twig', [
            'first_name' => $user?->getFirstname,
            'last_name'  => $user?->getLastname,
        ]);
    }
}