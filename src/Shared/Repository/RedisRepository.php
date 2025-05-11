<?php

declare(strict_types=1);

namespace App\Shared\Repository;

use Redis;

class RedisRepository implements EphemeralPersistence
{
    private Redis $redis;

    public function __construct(Redis $redisConnection)
    {
        $this->redis = $redisConnection;
    }

    public function get($aKey)
    {
        return $this->redis->get($aKey);
    }

    public function set($aKey, $aValue, ?int $aTTLInSeconds = null)
    {
        $aTTLInSeconds === null
            ? $this->redis->set($aKey, $aValue)
            : $this->redis->setex($aKey, $aValue, $aTTLInSeconds);

        return $this;
    }

    public function del($aKey)
    {
        $this->redis->del($aKey);

        return $this;
    }

    public function setMulti($aKey, $anArrayKeyValuePairs, ?int $aTTLInSeconds = null)
    {
        $this->redis->hMset($aKey, $anArrayKeyValuePairs);
        if ($aTTLInSeconds !== null) {
            $this->redis->expire($aKey, $aTTLInSeconds);
        }

        return $this;
    }

    public function getMulti($aKey, $hashKeys)
    {
        return $this->redis->hMGet($aKey, $hashKeys);
    }

    public function incrementByKey($aHash, $aKey, $anAmountToIncrement)
    {
        $this->redis->hIncrBy($aHash, $aKey, $anAmountToIncrement);
    }

    public function exists($aKey)
    {
        return $this->redis->exists($aKey);
    }

    public function keys()
    {
        return $this->redis->keys('*');
    }
}
