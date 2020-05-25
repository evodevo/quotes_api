<?php

declare(strict_types=1);

namespace QuotesAPI\Infrastructure\Cache;

use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class AsyncCacheStorage
 * @package QuotesAPI\Infrastructure\Cache
 */
class AsyncCacheStorage implements CacheStorage
{
    /**
     * Default cache lifetime in seconds.
     */
    const DEFAULT_CACHE_LIFETIME = 60;

    /**
     * How long to wait for cache update (in miliseconds).
     */
    const DEFAULT_SLA = 5000;

    /**
     * How many times to poll cache for update.
     */
    const MAX_CACHE_POLL_ATTEMPTS = 10;

    /**
     * @var AdapterInterface
     */
    protected $cache;

    /**
     * @var MessageBusInterface
     */
    protected $messageBus;

    /**
     * @var CacheLock
     */
    protected $cacheLock;

    /**
     * @var int
     */
    protected $cacheLifetime;

    /**
     * Amount of time, in milliseconds, in which stampede protection is guaranteed.
     *
     * @var int
     */
    protected $sla;

    /**
     * Amount of times every process will poll within $sla time.
     *
     * @var int
     */
    protected $attempts = self::MAX_CACHE_POLL_ATTEMPTS;

    /**
     * AsyncCacheStorage constructor.
     * @param AdapterInterface $cache
     * @param MessageBusInterface $messageBus
     * @param CacheLock $cacheLock
     * @param int $cacheLifetime
     * @param int $sla
     */
    public function __construct(
        AdapterInterface $cache,
        MessageBusInterface $messageBus,
        CacheLock $cacheLock,
        $cacheLifetime = self::DEFAULT_CACHE_LIFETIME,
        $sla = self::DEFAULT_SLA
    ) {
        $this->cache = $cache;
        $this->messageBus = $messageBus;
        $this->cacheLock = $cacheLock;
        $this->cacheLifetime = $cacheLifetime;
        $this->sla = $sla;
    }

    /**
     * @param $key
     * @param callable $callback
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function get(string $key, callable $callback = null)
    {
        $cacheItem = $this->cache->getItem($key);

        if (!$cacheItem->isHit() && $callback !== null) {
            $token  = $this->cacheLock->lock("$key:lock", CacheLock::DEFAULT_LOCK_TIMEOUT);
            if ($token !== null) {
                $value = $callback();

                $this->setAsync($key, $value, $token);

                return $value;
            } else {
                $cacheItem = $this->waitForCacheEntry($key);
            }
        }

        return $cacheItem->get();
    }

    /**
     * @param $key
     * @param $value
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function set(string $key, $value)
    {
        $cacheItem = $this->cache->getItem($key);
        $cacheItem->expiresAfter($this->cacheLifetime);

        $cacheItem->set($value);
        $this->cache->save($cacheItem);
    }

    /**
     * @param string $key
     * @param $value
     * @param string|null $token
     */
    protected function setAsync(string $key, $value, ?string $token)
    {
        $this->messageBus->dispatch(new CacheMessage($key, $value, $token));
    }

    /**
     * @param $key
     * @return \Symfony\Component\Cache\CacheItem
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function waitForCacheEntry(string $key): CacheItemInterface
    {
        $attempts = $this->attempts;
        $cacheItem = $this->cache->getItem($key);
        while (--$attempts > 0 && !$cacheItem->isHit() && $this->sleep()) {
            $cacheItem = $this->cache->getItem($key);
        }

        return $cacheItem;
    }

    /**
     * @return bool
     */
    protected function sleep(): bool
    {
        $interval = $this->sla / $this->attempts;
        usleep(1000 * $interval);

        return true;
    }
}