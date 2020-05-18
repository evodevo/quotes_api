<?php

namespace spec\QuotesAPI\Infrastructure\Cache;

use PhpSpec\ObjectBehavior;
use QuotesAPI\Infrastructure\Cache\AsyncCacheStorage;
use QuotesAPI\Infrastructure\Cache\CacheLock;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Cache\ItemInterface;
use QuotesAPI\Tests\spec\QuotesAPI\Infrastructure\Cache\StubCacheItem;
use QuotesAPI\Tests\spec\QuotesAPI\Infrastructure\Cache\MockCallable;

/**
 * Class AsyncCacheStorageSpec
 * @package spec\QuotesAPI\Infrastructure\Cache
 */
class AsyncCacheStorageSpec extends ObjectBehavior
{
    /**
     * @var ArrayAdapter
     */
    private $cache;

    /**
     * AsyncCacheStorageSpec constructor.
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function __construct()
    {
        $this->cache = new ArrayAdapter();
        $cacheItem = $this->cache->getItem('test_key');
        $cacheItem->set('test_value');
        $this->cache->save($cacheItem);
        $this->cache->save($cacheItem);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|MessageBusInterface $messageBus
     * @param \PhpSpec\Wrapper\Collaborator|CacheLock $cacheLock
     */
    function let(MessageBusInterface $messageBus, CacheLock $cacheLock)
    {
        $this->beConstructedWith($this->cache, $messageBus, $cacheLock);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AsyncCacheStorage::class);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    function it_gets_data_from_cache()
    {
        $result = $this->get('test_key', function () {
            throw new \Exception('Cache update callback should not be called');
        });
        $result->shouldBe('test_value');
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|MockCallable $mockCallable
     * @param \PhpSpec\Wrapper\Collaborator|CacheLock $cacheLock
     * @throws \Psr\Cache\InvalidArgumentException
     */
    function it_locks_and_updates_cache_on_cache_miss(MockCallable $mockCallable, CacheLock $cacheLock)
    {
        $cacheLock->lock('missing_key:lock', CacheLock::DEFAULT_LOCK_TIMEOUT)
            ->willReturn('missing_key:lock')->shouldBeCalled();

        $mockCallable->__invoke('missing_key', 'missing_key:lock')->willReturn(null)->shouldBeCalled();

        $result = $this->get('missing_key', $mockCallable);
        $result->shouldBeNull();
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|MockCallable $mockCallable
     * @param \PhpSpec\Wrapper\Collaborator|CacheLock $cacheLock
     * @param \PhpSpec\Wrapper\Collaborator|AdapterInterface $cache
     * @param \PhpSpec\Wrapper\Collaborator|MessageBusInterface $messageBus
     * @throws \Psr\Cache\InvalidArgumentException
     */
    function it_waits_for_pending_cache_key(
        MockCallable $mockCallable,
        CacheLock $cacheLock,
        AdapterInterface $cache,
        MessageBusInterface $messageBus) {
        $this->beConstructedWith($cache, $messageBus, $cacheLock);

        $cacheMiss = new StubCacheItem('pending_key', null, false);
        $cacheHit = new StubCacheItem('pending_key', 'pending_value', true);
        $cache->getItem('pending_key')
            ->willReturn($cacheMiss, $cacheMiss, $cacheHit)
            ->shouldBeCalledTimes(3);

        $cacheLock->lock('pending_key:lock', CacheLock::DEFAULT_LOCK_TIMEOUT)
            ->willReturn(null)->shouldBeCalled();

        $mockCallable->__invoke('pending_key', 'pending_key:lock')->willReturn(null)->shouldNotBeCalled();

        $result = $this->get('pending_key', $mockCallable);
        $result->shouldBe('pending_value');
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|MockCallable $mockCallable
     * @param \PhpSpec\Wrapper\Collaborator|CacheLock $cacheLock
     * @throws \Psr\Cache\InvalidArgumentException
     */
    function it_times_out_waiting_for_cache_key(MockCallable $mockCallable, CacheLock $cacheLock)
    {
        $cacheLock->lock('pending_key:lock', CacheLock::DEFAULT_LOCK_TIMEOUT)
            ->willReturn(null)->shouldBeCalled();

        $mockCallable->__invoke('pending_key', 'pending_key:lock')->willReturn(null)->shouldNotBeCalled();

        $result = $this->get('pending_key', $mockCallable);
        $result->shouldBe(null);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    function it_updates_data_in_the_cache()
    {
        $this->set('test_key', 'test_value');

        $cacheItem = $this->cache->getItem('test_key');
        expect($cacheItem->isHit())->toBe(true);
    }
}
