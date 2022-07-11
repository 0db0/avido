<?php

namespace App\Security\CustomAuthenticators;

use App\Enum\UserRole;
use App\Service\UserRoleManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
//        private readonly Security $security,
        private readonly UserRoleManager $roleManager,
        private readonly RouterInterface $router
    ) {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $user = $token->getUser();

        if ($user === null) {
            throw new UserNotFoundException();
        }

        $redirectUrl = match (true) {
            $this->roleManager->hasRole($user, UserRole::Admin)     => $this->router->generate('web_admin_account'),
            $this->roleManager->hasRole($user, UserRole::Moderator) => $this->router->generate('web_moderator_account'),
            $this->roleManager->hasRole($user, UserRole::User)      => $this->router->generate('web_account'),
        };

        return new RedirectResponse($redirectUrl);
    }
}