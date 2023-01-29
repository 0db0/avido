<?php

namespace App\Utils\RequestDtoConverter;

use App\Utils\Attributes\QueryBy;
use App\Utils\Attributes\RequestFieldAliases;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class ValueExtractor
{
    public function __construct(private readonly ManagerRegistry $doctrine)
    {
    }

    /**
     * @throws ReflectionException
     */
    public function prepareConstructorArguments(ReflectionClass $class, array $payload): array
    {
        $aliases = !empty($class->getAttributes(RequestFieldAliases::class))
                    ? $class->getAttributes(RequestFieldAliases::class)[0]->newInstance()->aliases
                    : [];

        $arguments = [];
        foreach ($class->getConstructor()?->getParameters() as $parameter) {
            if ($this->isParameterOptionalAndNotPresentInPayload($parameter, $payload, $aliases)) {
                $arguments[$parameter->getPosition()] = null;
                continue;
            }

            if (class_exists($parameter->getType()?->getName())) {
                $repository = $this->doctrine->getRepository($parameter->getType()->getName());
                $property = $class->getProperty($parameter->getName());
                $findByKey = $property->getAttributes(QueryBy::class)[0]->newInstance()->queryKey;

                $arguments[$parameter->getPosition()] = $repository->findOneBy([$findByKey => $payload[$parameter->getName()] ?? null]);
                continue;
            }

            if (isset($payload[$parameter->getName()])) {
                $arguments[$parameter->getPosition()] = $payload[$parameter->getName()];
                continue;
            }

            $snakeCaseName = $this->transformToSnakeCase($parameter->getName());
            if (isset($payload[$snakeCaseName])) {
                $arguments[$parameter->getPosition()] = $payload[$snakeCaseName];
                continue;
            }

            $alias = $aliases[$parameter->getName()] ?? null;
            if ($alias && isset($payload[$alias])) {
                $arguments[$parameter->getPosition()] = $payload[$alias];
                continue;
            }

            throw new LogicException(
                sprintf('Field %s must be present in request payload.', $parameter->getName())
            );
        }

        return $arguments;
    }

    private function isParameterOptionalAndNotPresentInPayload(
        ReflectionParameter $parameter,
        array $payload,
        array $aliases
    ): bool {
        return  $parameter->allowsNull()
            && !isset($payload[$parameter->getName()])
            && array_key_exists($parameter->getName(), $aliases);
    }

    private function transformToSnakeCase(string $propertyName): string
    {
        $words = preg_split(
            '#([A-Z][^A-Z]*)#',
            $propertyName,
            null,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
        );

        foreach ($words as $key => $word) {
            if ($key === 0) {
                continue;
            }

            $words[$key] = lcfirst($word);
        }

        return implode('_', $words);
    }
}
