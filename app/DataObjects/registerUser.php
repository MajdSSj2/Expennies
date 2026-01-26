<?php

namespace App\DataObjects;

class registerUser
{

    /**
     * @param mixed $name
     * @param mixed $email
     * @param mixed $password
     */
    public function __construct( public string $name, public string $email, public string $password)
    {
    }
}