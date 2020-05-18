<?php

declare(strict_types=1);

namespace QuotesAPI\Infrastructure\Cache;

/**
 * Class CacheMessage
 * @package QuotesAPI\Infrastructure\Cache
 */
class CacheMessage
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
     * @var null
     */
    private $token;

    /**
     * CacheMessage constructor.
     * @param string $key
     * @param $value
     * @param null $token
     */
    public function __construct(string $key, $value, $token = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }
}