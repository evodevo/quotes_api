<?php

declare(strict_types=1);

namespace QuotesAPI\Domain;

/**
 * Interface AuthorRepository
 * @package QuotesAPI\Domain
 */
interface AuthorRepository
{
    /**
     * @param string $slug
     * @return Author|null
     */
    public function findBySlug(string $slug): ?Author;

    /**
     * @param Author $author
     * @return void
     */
    public function add(Author $author);

    /**
     * @param Author $author
     * @return void
     */
    public function remove(Author $author);
}