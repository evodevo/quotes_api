<?php

declare(strict_types=1);

namespace QuotesAPI\Application\Query\Shout\Handler;

use QuotesAPI\Application\Exception\NotFoundException;
use QuotesAPI\Application\Query\Shout\ShoutQuery;
use QuotesAPI\Domain\QuoteRepository;
use QuotesAPI\Infrastructure\Cache\CacheMessage;
use QuotesAPI\Infrastructure\Cache\CacheStorage;
use Symfony\Component\Messenger\MessageBusInterface;

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
     * @var MessageBusInterface
     */
    protected $messageBus;

    /**
     * CachedQuotesQueryHandler constructor.
     * @param QueryHandler $queryHandler
     * @param CacheStorage $cacheStorage
     * @param MessageBusInterface $messageBus
     */
    public function __construct(
        QueryHandler $queryHandler,
        CacheStorage $cacheStorage,
        MessageBusInterface $messageBus
    ) {
        $this->queryHandler = $queryHandler;
        $this->cacheStorage = $cacheStorage;
        $this->messageBus = $messageBus;
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
            function($key, $token) use ($authorSlug) {
                return $this->updateCache($key, $token, $authorSlug);
            }
        );

        if ($quotes === null) {
            throw new NotFoundException('Author not found with slug ' . $authorSlug);
        }

        return !empty($quotes) ? array_slice($quotes, 0, $limit) : [];
    }

    /**
     * @param $key
     * @param $token
     * @param $authorSlug
     * @return array
     */
    private function updateCache($key, $token, $authorSlug): array
    {
        try {
            $quotes = $this->queryHandler->handle(
                new ShoutQuery($authorSlug, 0)
            );
        } catch (NotFoundException $e) {
            $this->messageBus->dispatch(new CacheMessage($key, null, $token));

            throw $e;
        }

        $this->messageBus->dispatch(new CacheMessage($key, $quotes, $token));

        return $quotes;
    }

    /**
     * @param $author
     * @return string
     */
    private function getCacheKey($author): string
    {
        return sprintf('author.%s.quotes', $author);
    }
}