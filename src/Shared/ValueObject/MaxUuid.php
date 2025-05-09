<?php

namespace App\Shared\ValueObject;

use Symfony\Component\Uid\MaxUuid as SymfonyMaxUuid;

class MaxUuid extends SymfonyMaxUuid
{
    public const BINARY_VALUE = "\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF";
    public const STRING_VALUE = "FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF";

    protected static MaxUuid $instance;

    public static function getInstance(): MaxUuid
    {
        if (!isset(static::$instance)) {
            static::$instance = new MaxUuid();
        }
        return static::$instance;
    }
}