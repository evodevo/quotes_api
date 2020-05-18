<?php

namespace spec\QuotesAPI\Domain;

use Faker\Factory;
use PhpSpec\ObjectBehavior;
use QuotesAPI\Domain\Author;
use QuotesAPI\Domain\Quote;

/**
 * Class QuoteSpec
 * @package spec\QuotesAPI\Domain
 */
class QuoteSpec extends ObjectBehavior
{
    /**
     * @var \Faker\Generator
     */
    private $seeder;

    /**
     * @var string
     */
    private $authorName;

    /**
     * @var null|int
     */
    private $id;

    /**
     * AuthorSpec constructor.
     */
    public function __construct()
    {
        $this->seeder = Factory::create();
    }

    function let()
    {
        $this->beConstructedWith(
            new Author(
                $this->authorName = $this->seeder->name
            ),
            'Test quote',
            $this->id = $this->seeder->randomNumber()
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Quote::class);
    }

    public function it_has_id()
    {
        $this->getId()->shouldReturn($this->id);
    }

    public function it_shouts_quotes()
    {
        $this->shout()->getText()->shouldReturn('TEST QUOTE!');
    }
}
