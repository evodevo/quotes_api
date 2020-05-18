<?php

declare(strict_types=1);

namespace QuotesAPI\Infrastructure\Http\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\FOSRestBundle;
use FOS\RestBundle\Request\ParamFetcherInterface;
use QuotesAPI\Application\Query\Shout\ShoutQuery;
use QuotesAPI\Application\Query\Shout\Handler\QueryHandler;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class QuotesController
 * @package QuotesAPI\Infrastructure\Http\Controller
 */
class QuotesController extends FOSRestBundle
{
    /**
     * @Rest\Get("/shout/{authorSlug}")
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements={"rule" = "\d+", "error_message" = "limit must be an integer"},
     *     strict=true,
     *     default="0",
     *     description="Number of quotes to return."
     * )
     * @SWG\Parameter(
     *     name="authorSlug",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="A slug made from authors name e.g. steve-jobs"
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="number",
     *     description="The field used to limit returned quotes"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns quotes by a given author",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(type="string")
     *     )
     * )
     * @SWG\Response(response="400", description="Returned when query validation fails")
     * @SWG\Response(response="404", description="Returned when author not found with a given slug")
     * @SWG\Tag(name="quotes")
     *
     * @param string $authorSlug
     * @param ParamFetcherInterface $paramFetcher
     * @param QueryHandler $quotesQueryHandler
     *
     * @return JsonResponse
     */
    public function shoutQuotes(
        string $authorSlug,
        ParamFetcherInterface $paramFetcher,
        QueryHandler $quotesQueryHandler
    ): JsonResponse {
        $quotesQuery = new ShoutQuery($authorSlug, (int)$paramFetcher->get('limit'));

        $quotes = $quotesQueryHandler->handle($quotesQuery);

        return (new JsonResponse($quotes))->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}