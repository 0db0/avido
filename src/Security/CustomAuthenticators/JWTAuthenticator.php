<?php

declare(strict_types=1);

namespace App\Security\CustomAuthenticators;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

final class JWTAuthenticator implements AuthenticatorInterface, AuthenticationEntryPointInterface
{
    private AuthenticatorInterface $jwtAuthenticator;

    public function supports(Request $request): ?bool
    {
        return $this->jwtAuthenticator->supports($request);
    }

    public function authenticate(Request $request): Passport
    {

        return $this->jwtAuthenticator->authenticate($request);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return $this->jwtAuthenticator->onAuthenticationSuccess($request, $token, $firewallName);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return $this->jwtAuthenticator->onAuthenticationFailure($request, $exception);
    }

    public function start(Request $request, AuthenticationException $authException = null): ?Response
    {
        return $this->jwtAuthenticator->start($request, $authException);
    }

    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        return $this->jwtAuthenticator->createToken($passport, $firewallName);
    }

    public function setBaseAuthenticator(AuthenticatorInterface $baseAuthenticator): void
    {
        $this->jwtAuthenticator = $baseAuthenticator;
    }
}
