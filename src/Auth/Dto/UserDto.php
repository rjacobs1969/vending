<?php

namespace App\Auth\Dto;

use Symfony\Component\Validator\Constraints as Assert;

use App\Auth\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 5, max: 500)]
        public readonly string $username,

        #[Assert\Length(min: 5, max: 500)]
        public readonly ?string $password,

        public readonly array $roles,
    ) {
    }

    public function update(?User $user = null): User
    {
        if (!$user) {
            $user = new User();
        }
        $result = $user;
        $result = $result->setUsername($this->username);

        if ($this->password !== null) {
            $result = $result->setPassword($this->password);
        }

        $result = $result->setRoles($this->roles);

        return $result;
    }
}