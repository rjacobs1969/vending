<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Shared\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Bridge\Doctrine\Types\AbstractUidType;

use Symfony\Component\Uid\Uuid;

final class BinaryUuidType extends AbstractUidType
{
    public const NAME = 'uuid';

    public function getName(): string
    {
        return self::NAME;
    }

    protected function getUidClass(): string
    {
        return Uuid::class;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getBinaryTypeDeclarationSQL([
            'length' => 16,
            'fixed' => true,
        ]);
    }

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        $toString = 'toBinary';

        if ($value instanceof AbstractUid) {
            return $value->$toString();
        }

        if (null === $value || '' === $value) {
            return null;
        }

        if (!\is_string($value)) {
            $this->throwInvalidType($value);
        }

        try {
            return $this->getUidClass()::fromString($value)->$toString();
        } catch (\InvalidArgumentException $e) {
            $this->throwValueNotConvertible($value, $e);
        }
    }
}