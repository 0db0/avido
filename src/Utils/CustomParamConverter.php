<?php

namespace App\Utils;

use App\Exception\ConstraintViolationException;
use App\Utils\Attributes\Mapped;
use Doctrine\Persistence\ManagerRegistry;
use ReflectionException;
use ReflectionProperty;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\matches;

class CustomParamConverter implements ParamConverterInterface
{
    public function __construct(
        private readonly RequestDtoValidator $validator,
        private readonly ManagerRegistry $doctrine
    ) {
    }

    /**
     * @throws ReflectionException
     * @throws ConstraintViolationException
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $reflectionAction = new \ReflectionMethod($request->attributes->get('_controller'));
        $parameters = $reflectionAction->getParameters();

        foreach ($parameters as $parameter) {
            if ($configuration->getClass() === $parameter->getType()?->getName()) {
                $reflectionClass = new \ReflectionClass($configuration->getClass());
                $properties = $reflectionClass->getProperties();

                $payload = match ($request->getContentType()) {
                    'form' => $request->request->all(),
                    'json' => $request->toArray(),
                };

                $arguments = $this->getArguments($properties, $payload);

                try {
                    $objectDto = $reflectionClass->newInstance(...$arguments);
                } catch (ReflectionException $e) {
//                    todo: loggin exception
                    return false;
                }

                $errors = $this->validator->validate($objectDto);
                if ($errors->count() > 0) {
                    $message = $this->validator->prepareMessage($errors);

                    throw new ConstraintViolationException($message);
                }

                $request->attributes->set($configuration->getName(), $objectDto);

                return true;
            }
        }

        return false;
    }

    // refactor to reflection
    public function supports(ParamConverter $configuration): bool
    {
        return str_starts_with($configuration->getClass(), 'App\Dto\Request\\');
    }

    /**
     * @param ReflectionProperty[] $properties
     */
    private function getArguments(array $properties, array $payload): array
    {
        foreach ($properties as $property) {
            if (class_exists($property->getType()?->getName())) {
                $repository = $this->doctrine->getRepository($property->getType()->getName());
                $attributes = $property->getAttributes(Mapped::class);
                $findByKey = $attributes[0]->newInstance()->mappedKey;

                $arguments[$property->getName()] = $repository->findOneBy([$findByKey => $payload[$property->getName()] ?? null]);
            }elseif ($argument = $payload[$property->getName()] ?? null) {
                    $arguments[$property->getName()] = $argument;
            } elseif ($argument = $payload[$this->transformToSnakeCase($property->getName())] ?? null) {
                $arguments[$property->getName()] = $argument;
            } else {
                throw new \InvalidArgumentException(
                    sprintf('Field %s cannot be null.', $property->getName())
                );
            }
        }

        $orderedArgument = array_map(static fn(ReflectionProperty $property): string => $property->getName(), $properties);

        return array_merge(array_fill_keys($orderedArgument, ''), $arguments ?? []);
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
