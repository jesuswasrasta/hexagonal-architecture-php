<?php

namespace App\Domain;

interface UsersInterface
{
    public function add($username): ResultInterface;

    public function toArray(): array;
}
