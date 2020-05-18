<?php

declare(strict_types=1);

namespace QuotesAPI\Application\Query\Shout;

/**
 * Class QuotesQuery
 * @package QuotesAPI\Application
 */
class ShoutQuery
{
    /**
     * @var string
     */
    private $authorSlug;

    /**
     * @var int
     */
    private $limit;

    /**
     * QuotesQuery constructor.
     * @param string $authorSlug
     * @param int $limit
     */
    public function __construct(string $authorSlug, int $limit)
    {
        $this->authorSlug = $authorSlug;
        $this->limit = $limit;
    }

    /**
     * @return string
     */
    public function getAuthorSlug(): string
    {
        return $this->authorSlug;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}