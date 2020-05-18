<?php

declare(strict_types=1);

namespace QuotesAPI\Infrastructure\Cache;

use SebastianBergmann\CodeCoverage\Report\PHP;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class CacheMessageHandler
 * @package QuotesAPI\Infrastructure\Cache
 */
class CacheMessageHandler implements MessageHandlerInterface
{
    /**
     * @var CacheStorage
     */
    protected $cache;

    /**
     * @var CacheLock
     */
    protected $cacheLock;

    /**
     * CacheMessageHandler constructor.
     * @param CacheStorage $cache
     * @param CacheLock $cacheLock
     */
    public function __construct(CacheStorage $cache, CacheLock $cacheLock)
    {
        $this->cache = $cache;
        $this->cacheLock = $cacheLock;
    }

    /**
     * @param CacheMessage $message
     */
    public function __invoke(CacheMessage $message)
    {
        $this->cache->set($message->getKey(), $message->getValue());

        $token = $message->getToken();
        if ($token) {
            $this->cacheLock->unlock($token);
        }
    }
}