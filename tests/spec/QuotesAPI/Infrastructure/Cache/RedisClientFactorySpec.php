<?php

namespace spec\QuotesAPI\Infrastructure\Cache;

use PhpSpec\ObjectBehavior;
use Predis\Client;
use QuotesAPI\Infrastructure\Cache\RedisClientFactory;

/**
 * Class RedisClientFactorySpec
 * @package spec\QuotesAPI\Infrastructure\Cache
 */
class RedisClientFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(RedisClientFactory::class);
    }

    function it_creates_redis_client()
    {
        $this::create('redis://redis')->shouldReturnAnInstanceOf(Client::class);
    }
}
