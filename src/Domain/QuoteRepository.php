<?php

declare(strict_types=1);

namespace QuotesAPI\Domain;

/**
 * Interface QuoteRepository
 * @package QuotesAPI\Domain
 */
interface QuoteRepository
{
    const DEFAULT_QUOTES_LIMIT = 10;

    /**
     * @param string $authorSlug
     * @param int $limit
     * @return array
     */
    public function findAllByAuthorSlug(string $authorSlug, int $limit = null): array;

    /**
     * @param Quote $quote
     * @return void
     */
    public function add(Quote $quote);
}