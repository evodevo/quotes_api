<?php

namespace spec\QuotesAPI\Infrastructure\Cache;

use PhpSpec\ObjectBehavior;
use Predis\Client;
use QuotesAPI\Infrastructure\Cache\RedisCacheLock;

/**
 * Class RedisCacheLockSpec
 * @package spec\QuotesAPI\Infrastructure\Cache
 */
class RedisCacheLockSpec extends ObjectBehavior
{
    /**
     * @param \PhpSpec\Wrapper\Collaborator|Client $client
     */
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RedisCacheLock::class);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|Client $client
     */
    function it_acquires_lock(Client $client)
    {
        $client->set(
            'my_lock',
            '1',
            'PX',
            5000,
            'NX'
        )->shouldBeCalled();

        $this->lock('my_lock', 5);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|Client $client
     */
    function it_releases_lock(Client $client)
    {
        $client->del('my_lock')->shouldBeCalled();

        $this->unlock('my_lock');
    }
}
