<?php

namespace spec\QuotesAPI\Application\Query\Shout;

use Faker\Factory;
use PhpSpec\ObjectBehavior;
use QuotesAPI\Application\Query\Shout\ShoutQuery;

/**
 * Class QuotesQuerySpec
 * @package spec\QuotesAPI\Application
 */
class ShoutQuerySpec extends ObjectBehavior
{
    /**
     * @var \Faker\Generator
     */
    private $seeder;

    /**
     * @var string
     */
    private $authorSlug;

    /**
     * @var int
     */
    private $limit;

    /**
     * QuotesQuerySpec constructor.
     */
    public function __construct()
    {
        $this->seeder = Factory::create();
    }

    function let()
    {
        $this->beConstructedWith(
            $this->authorSlug = $this->seeder->slug(2),
            $this->limit = $this->seeder->randomNumber());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ShoutQuery::class);
    }

    public function it_has_author_slug()
    {
        $this->getAuthorSlug()->shouldReturn($this->authorSlug);
    }

    public function it_has_limit()
    {
        $this->getLimit()->shouldReturn($this->limit);
    }
}
