<?php

declare(strict_types=1);

namespace QuotesAPI\Application\Query\Shout\Handler;

use QuotesAPI\Application\Exception\InvalidArgumentException;
use QuotesAPI\Application\Exception\OutOfRangeException;
use QuotesAPI\Application\Query\Shout\ShoutQuery;
use QuotesAPI\Domain\QuoteRepository;

/**
 * Class ValidatedQuotesQueryHandler
 * @package QuotesAPI\Application
 */
class ValidatedQueryHandler implements QueryHandler
{
    /**
     * @var QueryHandler
     */
    private $queryHandler;

    /**
     * @var int
     */
    private $maxQuotesLimit;

    /**
     * ValidatedQuotesQueryHandler constructor.
     * @param QueryHandler $queryHandler
     * @param int $maxQuotesLimit
     */
    public function __construct(
        QueryHandler $queryHandler,
        $maxQuotesLimit = QuoteRepository::DEFAULT_QUOTES_LIMIT
    ) {
        $this->queryHandler = $queryHandler;
        $this->maxQuotesLimit = $maxQuotesLimit;
    }

    /**
     * @param ShoutQuery $query
     * @return array
     */
    public function handle(ShoutQuery $query): array
    {
        if ($query->getLimit() < 0 || $query->getLimit() > $this->maxQuotesLimit) {
            throw new OutOfRangeException(
                sprintf("Limit out of range, allowed values [0 - %s}]", $this->maxQuotesLimit)
            );
        }

        if (!$query->getAuthorSlug()) {
            throw new InvalidArgumentException('Author slug cannot be empty');
        }

        if (!preg_match('/^[-a-z0-9]+$/', $query->getAuthorSlug())) {
            throw new InvalidArgumentException(
                'Author slug must consist of lowercase letters, numbers and hyphens only'
            );
        }

        return $this->queryHandler->handle($query);
    }
}