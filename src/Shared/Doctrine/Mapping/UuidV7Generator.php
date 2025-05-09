<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Shared\Doctrine\Mapping;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Symfony\Component\Uid\Factory\TimeBasedUuidFactory;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

class UuidV7Generator extends AbstractIdGenerator
{
    private readonly TimeBasedUuidFactory $protoFactory;
    private TimeBasedUuidFactory $factory;
    private ?string $entityGetter = null;

    public function __construct(?TimeBasedUuidFactory $factory = null)
    {
        $this->protoFactory = $this->factory = $factory ?? new TimeBasedUuidFactory(UuidV7::class);
    }

    /**
     * doctrine/orm < 2.11 BC layer.
     */
    public function generate(EntityManager $em, $entity): Uuid
    {
        return $this->generateId($em, $entity);
    }

    public function generateId(EntityManagerInterface $em, $entity): Uuid
    {
        if (null !== $this->entityGetter) {
            if (\is_callable([$entity, $this->entityGetter])) {
                return $this->factory->create($entity->{$this->entityGetter}());
            }

            return $this->factory->create($entity->{$this->entityGetter});
        }

        return $this->factory->create();
    }


    public function timeBased(Uuid|string|null $node = null): static
    {
        $clone = clone $this;
        $clone->factory = $clone->protoFactory;
        $clone->entityGetter = null;

        return $clone;
    }
}
