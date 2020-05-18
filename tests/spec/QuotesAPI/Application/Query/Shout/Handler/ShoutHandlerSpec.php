<?php

namespace spec\QuotesAPI\Application\Query\Shout\Handler;

use Faker\Factory;
use PhpSpec\ObjectBehavior;
use QuotesAPI\Application\Exception\NotFoundException;
use QuotesAPI\Application\Query\Shout\ShoutQuery;
use QuotesAPI\Application\Query\Shout\Handler\ShoutHandler;
use QuotesAPI\Domain\Author;
use QuotesAPI\Domain\AuthorRepository;
use QuotesAPI\Domain\Quote;
use QuotesAPI\Domain\QuoteRepository;

/**
 * Class QuotesQueryHandlerSpec
 * @package spec\QuotesAPI\Application
 */
class ShoutHandlerSpec extends ObjectBehavior
{
    const MAX_QUOTES_LIMIT = 10;

    /**
     * @var \Faker\Generator
     */
    private $seeder;

    /**
     * @var string
     */
    private $author;

    /**
     * QuotesQueryHandlerSpec constructor.
     */
    public function __construct()
    {
        $this->seeder = Factory::create();

        $this->author = new Author($this->seeder->name);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ShoutHandler::class);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|AuthorRepository $authorRepository
     * @param \PhpSpec\Wrapper\Collaborator|QuoteRepository $quoteRepository
     */
    function let(AuthorRepository $authorRepository, QuoteRepository $quoteRepository)
    {
        $this->beConstructedWith($authorRepository, $quoteRepository);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|AuthorRepository $authorRepository
     * @param \PhpSpec\Wrapper\Collaborator|QuoteRepository $quoteRepository
     */
    function it_returns_shouted_quotes_by_author_slug(AuthorRepository $authorRepository, QuoteRepository $quoteRepository)
    {
        $authorRepository->findBySlug($this->author->getSlug())
            ->willReturn($this->author)
            ->shouldBeCalled();

        $quoteRepository->findAllByAuthorSlug($this->author->getSlug(), 10)->willReturn([
            new Quote($this->author, 'First quote.'),
            new Quote($this->author, 'Second quote.'),
        ])->shouldBeCalled();

        $query = new ShoutQuery($this->author->getSlug(), 10);

        $result = $this->handle($query);
        $result->shouldBeArray();
        $result->shouldHaveCount(2);
        $result->shouldContain('FIRST QUOTE!');
        $result->shouldContain('SECOND QUOTE!');
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|AuthorRepository $authorRepository
     */
    function it_throws_an_exception_when_author_not_found(AuthorRepository $authorRepository)
    {
        $query = new ShoutQuery($this->author->getSlug(), 1);

        $authorRepository->findBySlug($this->author->getSlug())
            ->willReturn(null)
            ->shouldBeCalled();

        $this->shouldThrow(NotFoundException::class)->duringHandle($query);
    }
}
