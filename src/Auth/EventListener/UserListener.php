<?php

namespace App\Auth\EventListener;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use App\Auth\Entity\User;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

//#[AsDoctrineListener(event: Events::prePersist, priority: 500)]
//#[AsDoctrineListener(event: Events::preUpdate, priority: 500)]
#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: User::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: User::class)]
class UserListener
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
    ) {}

    public function prePersist(User $user, PrePersistEventArgs $args): void
    {
        $password = $user->getPassword();
        $password = $this->encodePassword($user, $password);
        $user->setPassword($password);
    }

    public function preUpdate(User $user, PreUpdateEventArgs $args): void
    {
        if ($args->hasChangedField('password')) {
            $password = $args->getNewValue('password');
            $password = $this->encodePassword($user, $password);
            $user->setPassword($password);
        }
    }

    private function encodePassword(User $user, string $password): string
    {
        return $this->hasher->hashPassword($user, $password);
    }
}
