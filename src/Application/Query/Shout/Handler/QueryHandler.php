<?php

declare(strict_types=1);

namespace QuotesAPI\Application\Query\Shout\Handler;

use QuotesAPI\Application\Query\Shout\ShoutQuery;

/**
 * Interface QuotesQueryHandler
 * @package QuotesAPI\Application
 */
interface QueryHandler
{
    /**
     * @param ShoutQuery $query
     * @return mixed
     */
    public function handle(ShoutQuery $query): array;
}