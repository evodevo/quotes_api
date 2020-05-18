<?php

namespace spec\QuotesAPI\Domain;

use Ausi\SlugGenerator\SlugGenerator;
use Faker\Factory;
use PhpSpec\ObjectBehavior;
use QuotesAPI\Domain\Author;
use QuotesAPI\Domain\Quote;

/**
 * Class AuthorSpec
 * @package spec\QuotesAPI\Domain
 */
class AuthorSpec extends ObjectBehavior
{
    /**
     * @var \Faker\Generator
     */
    private $seeder;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
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
            $this->name = $this->seeder->name,
            $this->id = $this->seeder->randomNumber()
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Author::class);
    }

    public function it_has_id()
    {
        $this->getId()->shouldReturn($this->id);
    }

    public function it_has_name()
    {
        $this->getName()->shouldReturn($this->name);
    }

    public function it_has_slug()
    {
        $this->getSlug()->shouldReturn((new SlugGenerator())->generate($this->name));
    }

    public function it_can_add_quote()
    {
        $this->getQuotes()->shouldHaveCount(0);

        $this->addQuote('Test quote');

        $quote = new Quote($this->getWrappedObject(), 'Test quote');

        $this->getQuotes()->shouldHaveCount(1);
        $this->getQuotes()->shouldIterateLike([$quote]);
    }
}
