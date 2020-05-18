<?php

declare(strict_types=1);

namespace QuotesAPI\Infrastructure\Cache;

use Predis\Client;

/**
 * Class RedisCacheLock
 * @package QuotesAPI\Infrastructure\Cache
 */
class RedisCacheLock implements CacheLock
{
    /**
     * @var Client
     */
    private $client;

    /**
     * RedisCacheLock constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $key
     * @param float $timeout
     * @return string|null
     */
    public function lock($key, $timeout = self::DEFAULT_LOCK_TIMEOUT): ?string
    {
        $result = $this->client->set($key, '1', 'PX', (int)(1000 * $timeout), 'NX');

        $ok = $result === true || (string)$result === 'OK';

        return $ok ? $key : null;
    }

    /**
     * @param $token
     * @return bool
     */
    public function unlock($token): bool
    {
        $result = $this->client->del($token);

        return $result > 0;
    }
}