<?php

declare(strict_types=1);

namespace App\Utils\RequestDtoConverter;

use App\Dto\Request\RequestDtoInterface;
use App\Exception\ConstraintViolationException;
use ReflectionClass;
use ReflectionException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

final class RequestDtoConverter implements ParamConverterInterface
{
    public function __construct(
        private readonly RequestDtoValidator $validator,
        private readonly ValueExtractor $valueExtractor,
    ) {
    }

    /**
     * @throws ReflectionException
     * @throws ConstraintViolationException
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $reflectionClass = new ReflectionClass($configuration->getClass());

        if ($request->getMethod() === Request::METHOD_GET) {
            $payload = $request->query->all();
        } else {
            $payload = match ($request->getContentType()) {
                'form' => $request->request->all(),
                'json' => $request->toArray(),
            };
        }

        $arguments = $this->valueExtractor->prepareConstructorArguments($reflectionClass, $payload);
        $objectDto = $reflectionClass->newInstance(...$arguments);

        $errors = $this->validator->validate($objectDto);
        if ($errors->count() > 0) {
            $message = $this->validator->prepareMessage($errors);

            throw new ConstraintViolationException($message);
        }

        $request->attributes->set($configuration->getName(), $objectDto);

        return true;
    }

    /**
     * @throws ReflectionException
     */
    public function supports(ParamConverter $configuration): bool
    {
        $class = $configuration->getClass();

        if (! $class) {
            return false;
        }

        return (new ReflectionClass($class))->implementsInterface(RequestDtoInterface::class);
    }
}
