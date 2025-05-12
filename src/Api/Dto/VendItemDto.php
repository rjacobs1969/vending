<?php

namespace App\Api\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

class VendItemDto
{
    public function __construct(
        #[Assert\Type(
            type: 'string',
            message: 'item must be an string value'
        )]
        #[Assert\NotBlank(
            message: 'item cannot be blank'
        )]
        #[Groups(["find"])]
        public readonly string $item,
    ) {
    }

    public function itemName(): string
    {
        return $this->item;
    }
}
