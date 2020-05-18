<?php

namespace spec\QuotesAPI\Application\Query\Shout\Handler;

use PhpSpec\ObjectBehavior;
use QuotesAPI\Application\Exception\InvalidArgumentException;
use QuotesAPI\Application\Exception\OutOfRangeException;
use QuotesAPI\Application\Query\Shout\ShoutQuery;
use QuotesAPI\Application\Query\Shout\Handler\QueryHandler;
use QuotesAPI\Application\Query\Shout\Handler\ValidatedQueryHandler;

class ValidatedQueryHandlerSpec extends ObjectBehavior
{
    /**
     * @param \PhpSpec\Wrapper\Collaborator|QueryHandler $queryHandler
     */
    function let(QueryHandler $queryHandler)
    {
        $this->beConstructedWith($queryHandler, 10);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ValidatedQueryHandler::class);
    }

    function it_should_throw_an_exception_if_no_author_slug_given()
    {
        $query = new ShoutQuery('', 1);

        $this->shouldThrow(InvalidArgumentException::class)->duringHandle($query);
    }

    function it_should_throw_an_exception_if_invalid_author_slug_is_given()
    {
        $query = new ShoutQuery('steve jobs', 1);

        $this->shouldThrow(InvalidArgumentException::class)->duringHandle($query);
    }

    function it_should_throw_an_exception_if_quotes_limit_is_negative()
    {
        $query = new ShoutQuery('steve-jobs', -1);

        $this->shouldThrow(OutOfRangeException::class)->duringHandle($query);
    }

    function it_should_throw_an_exception_if_quotes_limit_is_over_threshold()
    {
        $query = new ShoutQuery('steve-jobs', 11);

        $this->shouldThrow(OutOfRangeException::class)->duringHandle($query);
    }

    /**
     * @param \PhpSpec\Wrapper\Collaborator|QueryHandler $queryHandler
     */
    function it_handles_query_successfully(QueryHandler $queryHandler)
    {
        $query = new ShoutQuery('steve-jobs', 1);

        $queryHandler->handle($query)->shouldBeCalled();

        $this->handle($query);
    }
}
