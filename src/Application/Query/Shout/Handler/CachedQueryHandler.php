<?php

declare(strict_types=1);

namespace QuotesAPI\Application\Query\Shout\Handler;

use QuotesAPI\Application\Exception\NotFoundException;
use QuotesAPI\Application\Query\Shout\ShoutQuery;
use QuotesAPI\Domain\QuoteRepository;
use QuotesAPI\Infrastructure\Cache\CacheStorage;

/**
 * Class CachedQuotesQueryHandler
 * @package QuotesAPI\Application
 */
class CachedQueryHandler implements QueryHandler
{
    /**
     * @var QueryHandler
     */
    protected $queryHandler;

    /**
     * @var CacheStorage
     */
    protected $cacheStorage;

    /**
     * CachedQuotesQueryHandler constructor.
     * @param QueryHandler $queryHandler
     * @param CacheStorage $cacheStorage
     */
    public function __construct(QueryHandler $queryHandler, CacheStorage $cacheStorage)
    {
        $this->queryHandler = $queryHandler;
        $this->cacheStorage = $cacheStorage;
    }

    /**
     * @param ShoutQuery $quotesQuery
     * @return array
     * @throws NotFoundException
     */
    public function handle(ShoutQuery $quotesQuery): array
    {
        $limit = $quotesQuery->getLimit();
        if (!$limit) {
            $limit = QuoteRepository::DEFAULT_QUOTES_LIMIT;
        }

        $authorSlug = $quotesQuery->getAuthorSlug();

        $quotes = $this->cacheStorage->get(
            $this->getCacheKey($authorSlug),
            function() use ($authorSlug) {
                return $this->getAuthorQuotes($authorSlug);
            }
        );

        if ($quotes === null) {
            throw new NotFoundException('Author not found with slug ' . $authorSlug);
        }

        return !empty($quotes) ? array_slice($quotes, 0, $limit) : [];
    }

    /**
     * @param $authorSlug
     * @return array|null
     */
    private function getAuthorQuotes(string $authorSlug): ?array
    {
        try {
            $quotes = $this->queryHandler->handle(
                new ShoutQuery($authorSlug, 0)
            );
        } catch (NotFoundException $e) {
            return null;
        }

        return $quotes;
    }

    /**
     * @param $author
     * @return string
     */
    private function getCacheKey(string $author): string
    {
        return sprintf('author.%s.quotes', $author);
    }
}