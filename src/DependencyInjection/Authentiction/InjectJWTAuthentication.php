<?php

namespace App\DependencyInjection\Authentiction;

use App\Security\CustomAuthenticators\JWTAuthenticator as CustomAuthenticator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Symfony\Component\DependencyInjection\Reference;

final class InjectJWTAuthentication implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $baseAuthenticatorRef = new Reference(JWTAuthenticator::class);

        $primaryConnection = $container->getDefinition(CustomAuthenticator::class);
        $primaryConnection->addMethodCall('setBaseAuthenticator', [$baseAuthenticatorRef]);
    }
}