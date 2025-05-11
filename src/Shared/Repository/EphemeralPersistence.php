<?php

declare(strict_types=1);

namespace App\Shared\Repository;

interface EphemeralPersistence
{
    public function get($aKey);
    public function set($aKey, $aValue, ?int $aTTLInSeconds = null);
    public function del($aKey);
    public function setMulti($aKey, $anArrayKeyValuePairs, ?int $aTTLInSeconds = null);
    public function getMulti($aKey, $hashKeys);
    public function incrementByKey($aHash, $aKey, $anAmountToIncrement);
    public function exists($aKey);
    public function keys();
}
