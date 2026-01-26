<?php

namespace App\Validators;

use App\Contracts\RequestValidatorFactoryInterface;
use App\Contracts\RequestValidatorInterface;
use Psr\Container\ContainerInterface;

class RequestValidatorFactory implements RequestValidatorFactoryInterface
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function make(string $className): RequestValidatorInterface
    {
      $validator = $this->container->get($className);

      if (!$validator instanceof RequestValidatorInterface) {
          throw new \RuntimeException('class is not of type ' . RequestValidatorInterface::class);
      }
      return $validator;
    }
}