<?php

declare(strict_types=1);

namespace QuotesAPI\Infrastructure\Cache;

/**
 * Interface CacheStorage
 * @package QuotesAPI\Infrastructure\Cache
 */
interface CacheStorage
{
    /**
     * @param $key
     * @param callable|null $callback
     * @return mixed
     */
    public function get(string $key, callable $callback = null);

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set(string $key, $value);
}