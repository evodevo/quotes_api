<?php

declare(strict_types=1);

namespace QuotesAPI\Application\Query\Shout\Handler;

use QuotesAPI\Application\Exception\NotFoundException;
use QuotesAPI\Application\Query\Shout\ShoutQuery;
use QuotesAPI\Domain\AuthorRepository;
use QuotesAPI\Domain\Quote;
use QuotesAPI\Domain\QuoteRepository;

/**
 * Class QuotesQueryHandler
 * @package QuotesAPI\Application
 */
class ShoutHandler implements QueryHandler
{
    /**
     * @var AuthorRepository
     */
    protected $authorRepository;

    /**
     * @var QuoteRepository
     */
    protected $quoteRepository;

    /**
     * QuotesQueryHandler constructor.
     * @param AuthorRepository $authorRepository
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(AuthorRepository $authorRepository, QuoteRepository $quoteRepository)
    {
        $this->authorRepository = $authorRepository;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param ShoutQuery $quotesQuery
     * @return array
     */
    public function handle(ShoutQuery $quotesQuery): array
    {
        $author = $this->authorRepository->findBySlug($quotesQuery->getAuthorSlug());
        if (!$author) {
            throw new NotFoundException('Author not found with slug ' . $quotesQuery->getAuthorSlug());
        }

        $quotes = $this->quoteRepository->findAllByAuthorSlug(
            $quotesQuery->getAuthorSlug(),
            $quotesQuery->getLimit()
        );

        return array_map(function (Quote $quote) {
            return $quote->shout()->getText();
        }, $quotes);
    }
}