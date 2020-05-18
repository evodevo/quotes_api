<?php

declare(strict_types=1);

namespace QuotesAPI\Infrastructure\Cache;

/**
 * Interface CacheLock
 * @package QuotesAPI\Infrastructure\Cache
 */
interface CacheLock
{
    /**
     * Default lock timeout in seconds.
     */
    const DEFAULT_LOCK_TIMEOUT = 5.0;

    /**
     * @param $key
     * @param float $timeout
     * @return string|null
     */
    public function lock($key, $timeout = self::DEFAULT_LOCK_TIMEOUT): ?string;

    /**
     * @param $token
     * @return bool
     */
    public function unlock($token): bool;
}