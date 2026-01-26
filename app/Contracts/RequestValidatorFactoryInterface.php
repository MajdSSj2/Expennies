<?php

namespace App\Contracts;

Interface RequestValidatorFactoryInterface
{
    public function make(string $className): RequestValidatorInterface;
}