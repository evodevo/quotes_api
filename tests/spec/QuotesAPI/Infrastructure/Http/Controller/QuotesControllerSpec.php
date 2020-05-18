<?php

namespace spec\QuotesAPI\Infrastructure\Http\Controller;

use FOS\RestBundle\Request\ParamFetcherInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use QuotesAPI\Application\Query\Shout\ShoutQuery;
use QuotesAPI\Application\Query\Shout\Handler\QueryHandler;
use QuotesAPI\Infrastructure\Http\Controller\QuotesController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class QuotesControllerSpec
 * @package spec\QuotesAPI\Infrastructure\Http\Controller
 */
class QuotesControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(QuotesController::class);
    }

    /**
     * @param ParamFetcherInterface|\PhpSpec\Wrapper\Collaborator $paramFetcher
     * @param \PhpSpec\Wrapper\Collaborator|QueryHandler $queryHandler
     */
    function it_handles_quotes_queries(ParamFetcherInterface $paramFetcher, QueryHandler $queryHandler)
    {
        $quotes = ['Test quote 1', 'Test quote 2'];

        $queryHandler->handle(Argument::type(ShoutQuery::class))
            ->willReturn($quotes)
            ->shouldBeCalled();

        $result = $this->shoutQuotes('steve-jobs', $paramFetcher, $queryHandler);
        $result->shouldBeAnInstanceOf(JsonResponse::class);
        $result->getContent()->shouldBe(json_encode($quotes, JSON_UNESCAPED_UNICODE));
    }
}
