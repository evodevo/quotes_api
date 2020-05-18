<?php

namespace spec\QuotesAPI\Application\Query\Shout\Handler;

use Faker\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use QuotesAPI\Application\Query\Shout\Handler\CachedQueryHandler;
use QuotesAPI\Application\Exception\NotFoundException;
use QuotesAPI\Application\Query\Shout\ShoutQuery;
use QuotesAPI\Application\Query\Shout\Handler\QueryHandler;
use QuotesAPI\Domain\Author;
use QuotesAPI\Domain\Quote;
use QuotesAPI\Infrastructure\Cache\CacheStorage;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class CachedQuotesQueryHandlerSpec
 * @package spec\QuotesAPI\Application
 */
class CachedQueryHandlerSpec extends ObjectBehavior
{
    /**
     * @var \Faker\Generator
     */
    private $seeder;

    /**
     * CachedQuoteRepositorySpec constructor.
     */
    public function __construct()
    {
        $this->seeder = Factory::create();
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|QueryHandler $queryHandler
     * @param \PhpSpec\Wrapper\Collaborator|CacheStorage $cacheStorage
     * @param \PhpSpec\Wrapper\Collaborator|MessageBusInterface $messageBus
     */
    function let(QueryHandler $queryHandler, CacheStorage $cacheStorage, MessageBusInterface $messageBus)
    {
        $this->beConstructedWith($queryHandler, $cacheStorage, $messageBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CachedQueryHandler::class);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|QueryHandler $queryHandler
     * @param \PhpSpec\Wrapper\Collaborator|CacheStorage $cacheStorage
     */
    function it_loads_cached_quotes_by_author_slug(QueryHandler $queryHandler, CacheStorage $cacheStorage)
    {
        $author = new Author($this->seeder->name);

        $quotes = $this->givenQuotes($author);

        $query = new ShoutQuery($author->getSlug(), 3);

        $cacheStorage->get(sprintf('author.%s.quotes', $author->getSlug()), Argument::type('callable'))
            ->willReturn($quotes)->shouldBeCalled();

        $queryHandler->handle($query)->shouldNotBeCalled();

        $quotes = $this->handle($query);
        $quotes->shouldBeArray();
        $quotes->shouldHaveCount(3);
        $quotes->shouldContain($quotes[0]);
        $quotes->shouldContain($quotes[1]);
        $quotes->shouldContain($quotes[2]);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|CacheStorage $cacheStorage
     */
    function it_returns_quotes_with_default_limit(CacheStorage $cacheStorage)
    {
        $author = new Author($this->seeder->name);

        $quotes = $this->givenQuotes($author);

        $query = new ShoutQuery($author->getSlug(), 0);

        $cacheStorage->get(sprintf('author.%s.quotes', $author->getSlug()), Argument::type('callable'))
            ->willReturn($quotes)->shouldBeCalled();

        $quotes = $this->handle($query);
        $quotes->shouldBeArray();
        $quotes->shouldHaveCount(3);
        $quotes->shouldContain($quotes[0]);
        $quotes->shouldContain($quotes[1]);
        $quotes->shouldContain($quotes[2]);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|CacheStorage $cacheStorage
     */
    function it_returns_quotes_with_limit_applied(CacheStorage $cacheStorage)
    {
        $author = new Author($this->seeder->name);

        $quotes = $this->givenQuotes($author);

        $query = new ShoutQuery($author->getSlug(), 2);

        $cacheStorage->get(sprintf('author.%s.quotes', $author->getSlug()), Argument::type('callable'))
            ->willReturn($quotes)->shouldBeCalled();

        $quotes = $this->handle($query);
        $quotes->shouldBeArray();
        $quotes->shouldHaveCount(2);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|CacheStorage $cacheStorage
     */
    function it_throws_an_exception_when_author_not_found_in_cache(CacheStorage $cacheStorage)
    {
        $author = new Author($this->seeder->name);

        $query = new ShoutQuery($author->getSlug(), 1);

        $cacheStorage->get(sprintf('author.%s.quotes', $author->getSlug()), Argument::type('callable'))
            ->willReturn(null)->shouldBeCalled();

        $this->shouldThrow(NotFoundException::class)->duringHandle($query);
    }

    /**
     * @param Author $author
     * @return array
     */
    private function givenQuotes(Author $author): array
    {
        $quote1 = new Quote($author, $this->seeder->sentence);
        $quote2 = new Quote($author, $this->seeder->sentence);
        $quote3 = new Quote($author, $this->seeder->sentence);

        return [
            $quote1,
            $quote2,
            $quote3,
        ];
    }
}
