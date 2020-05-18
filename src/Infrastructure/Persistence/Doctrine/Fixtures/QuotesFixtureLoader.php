<?php

namespace QuotesAPI\Infrastructure\Persistence\Doctrine\Fixtures;

use Ausi\SlugGenerator\SlugGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use QuotesAPI\Domain\Author;
use QuotesAPI\Domain\AuthorRepository;

/**
 * Class QuotesFixtureLoader
 * @package QuotesAPI\Infrastructure\Persistence\Doctrine\Fixtures
 */
class QuotesFixtureLoader extends Fixture
{
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    /**
     * @var SlugGenerator
     */
    private $slugGenerator;

    /**
     * QuotesFixtureLoader constructor.
     * @param AuthorRepository $authorRepository
     * @param SlugGenerator $slugGenerator
     */
    public function __construct(AuthorRepository $authorRepository, SlugGenerator $slugGenerator)
    {
        $this->authorRepository = $authorRepository;
        $this->slugGenerator = $slugGenerator;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $quotes = file_get_contents(__DIR__ . '/data/quotes.json');
        $quotes = json_decode($quotes, true);
        if (!$quotes || !isset($quotes['quotes'])) {
            $quotes['quotes'] = [];
        }

        foreach($quotes['quotes'] as $quote) {
            if (!isset($quote['author']) || !isset($quote['quote'])) {
                continue;
            }

            $authorSlug = $this->slugGenerator->generate($quote['author']);
            $author = $this->authorRepository->findBySlug($authorSlug);
            if (!$author) {
                $author = new Author($quote['author']);
            }

            $author->addQuote($quote['quote']);
            $this->authorRepository->add($author);
        }
    }
}