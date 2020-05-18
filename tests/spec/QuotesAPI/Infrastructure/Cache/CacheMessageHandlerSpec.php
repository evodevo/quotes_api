<?php

namespace spec\QuotesAPI\Infrastructure\Cache;

use PhpSpec\ObjectBehavior;
use QuotesAPI\Infrastructure\Cache\CacheLock;
use QuotesAPI\Infrastructure\Cache\CacheMessage;
use QuotesAPI\Infrastructure\Cache\CacheMessageHandler;
use QuotesAPI\Infrastructure\Cache\CacheStorage;

/**
 * Class CacheMessageHandlerSpec
 * @package spec\QuotesAPI\Infrastructure\Cache
 */
class CacheMessageHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CacheMessageHandler::class);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|CacheStorage $cacheStorage
     * @param \PhpSpec\Wrapper\Collaborator|CacheLock $cacheLock
     */
    function let(CacheStorage $cacheStorage, CacheLock $cacheLock)
    {
        $this->beConstructedWith($cacheStorage, $cacheLock);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|CacheStorage $cacheStorage
     * @param \PhpSpec\Wrapper\Collaborator|CacheLock $cacheLock
     */
    function it_handles_messages_by_storing_them_in_the_cache_and_unlocking(CacheStorage $cacheStorage, CacheLock $cacheLock)
    {
        $cacheStorage->set('test_key', 'test_value')->shouldBeCalled();
        $cacheLock->unlock('test_key:lock')->shouldBeCalled();

        $message = new CacheMessage('test_key', 'test_value', 'test_key:lock');
        $this($message);
    }
}
