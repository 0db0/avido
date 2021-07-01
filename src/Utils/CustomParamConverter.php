<?php

namespace App\Utils;

use App\Exception\ConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class CustomParamConverter implements ParamConverterInterface
{
    public function __construct(private RequestDtoValidator $validator) {}

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $reflectionAction = new \ReflectionMethod($request->attributes->get('_controller'));
        $parameters = $reflectionAction->getParameters();


        foreach ($parameters as $parameter) {
            if ($configuration->getClass() === $parameter->getType()->getName()) {
                $reflectionClass = new \ReflectionClass($configuration->getClass());
                $properties = $reflectionClass->getProperties();
                $arguments = $this->getArguments($properties, $request);

                try {
                    $objectDto = $reflectionClass->newInstance(...$arguments);
                } catch (\ReflectionException $e) {
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
    }

    public function supports(ParamConverter $configuration): bool
    {
        return strpos($configuration->getClass(), 'App\Dto\\') === 0;
    }

    /**
     * @param \ReflectionProperty[] $properties
     * @param Request $request
     * @return array
     */
    private function getArguments(array $properties, Request $request): array
    {
        foreach ($properties as $property) {
            if ($argument = $request->request->get($property->getName())) {
                $arguments[] = $argument;
            } elseif ($argument = $request->request->get($this->transformToSnakeCase($property->getName()))) {
                $arguments[] = $argument;
            } else {
                throw new \InvalidArgumentException(
                    sprintf('Field %s cannot be null.', $property->getName()));
            }
        }

        return $arguments ?? [];
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