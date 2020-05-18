<?php

declare(strict_types=1);

namespace QuotesAPI\Infrastructure\Cache;

use Predis\Client;

/**
 * Class RedisClientFactory
 * @package QuotesAPI\Infrastructure\Cache
 */
class RedisClientFactory
{
    /**
     * @param $redisDsn
     * @return Client
     */
    public static function create($redisDsn): Client
    {
        return new Client(str_replace('redis://', 'tcp://', $redisDsn));
    }
}