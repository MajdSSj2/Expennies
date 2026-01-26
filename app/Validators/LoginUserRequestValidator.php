<?php

namespace App\Validators;

use App\Contracts\RequestValidatorInterface;
use App\Contracts\UserServiceInterface;
use App\Entity\User;
use App\Exceptions\ValidationException;
use Doctrine\ORM\EntityManager;
use Valitron\Validator;

class LoginUserRequestValidator implements RequestValidatorInterface
{
    public function __construct()
    {
    }

    public function validate(array $data): array
    {
        $v = new Validator($data);
        $v->rule('required', ['email', 'password']);
        $v->rule('email', 'email');

        if (!$v->validate()) {
            throw new ValidationException($v->errors());
        }
        return $data;
    }
}