<?php

namespace App\Api\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

class VendItemDto
{
    private const MIN_NAME_LENGTH = 3;
    private const MAX_NAME_LENGTH = 128;

    public function __construct(
        #[Assert\Type(
            type: 'string',
            message: 'item must be an string value'
        )]
        #[Assert\NotBlank(
            message: 'item cannot be blank'
        )]
        #[Assert\Length(
            min: self::MIN_NAME_LENGTH,
            max: self::MAX_NAME_LENGTH,
            minMessage: 'item must be at least {{ limit }} characters long',
            maxMessage: 'item cannot be longer than {{ limit }} characters'
        )]
        #[OA\Property(example: "water")]
        #[Groups(["find"])]
        public readonly string $item,
    ) {
    }

    public function itemName(): string
    {
        return $this->item;
    }
}
