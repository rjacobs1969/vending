<?php

namespace App\Shared\ValueObject;

use Symfony\Component\Uid\NilUuid as SymfonyNilUuid;

class NilUuid extends SymfonyNilUuid
{
    public const BINARY_VALUE = "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00";
    public const STRING_VALUE = "00000000-0000-0000-0000-000000000000";

    protected static NilUuid $instance;

    public static function getInstance(): NilUuid
    {
        if (!isset(static::$instance)) {
            static::$instance = new NilUuid();
        }
        return static::$instance;
    }
}