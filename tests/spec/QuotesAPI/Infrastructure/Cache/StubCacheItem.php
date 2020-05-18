<?php

namespace QuotesAPI\Tests\spec\QuotesAPI\Infrastructure\Cache;

use Symfony\Contracts\Cache\ItemInterface;

/**
 * Class StubCacheItem
 * @package QuotesAPI\Tests\spec\QuotesAPI\Infrastructure\Cache
 */
class StubCacheItem implements ItemInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var bool
     */
    private $isHit;

    /**
     * StubCacheItem constructor.
     * @param string $key
     * @param $value
     * @param bool $isHit
     */
    public function __construct(string $key, $value, bool $isHit)
    {
        $this->key = $key;
        $this->value = $value;
        $this->isHit = $isHit;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param mixed $value
     * @return StubCacheItem
     */
    public function set($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param \DateInterval|int|null $time
     * @return StubCacheItem
     */
    public function expiresAfter($time): self
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function isHit(): bool
    {
        return $this->isHit;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return [];
    }

    /**
     * @param string|string[] $tags
     * @return ItemInterface
     */
    public function tag($tags): ItemInterface
    {
        return $this;
    }

    /**
     * @param \DateTimeInterface|null $expiration
     * @return StubCacheItem
     */
    public function expiresAt($expiration): self
    {
        return $this;
    }
}