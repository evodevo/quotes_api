<?php

declare(strict_types=1);

namespace QuotesAPI\Domain;

use Ausi\SlugGenerator\SlugGenerator;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Author
 * @package QuotesAPI\Domain
 */
class Author
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var ArrayCollection
     */
    protected $quotes;

    /**
     * Author constructor.
     * @param string $name
     * @param int|null $id
     */
    public function __construct(string $name, int $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->slug = (new SlugGenerator())->generate($this->name);
        $this->quotes = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $quote
     * @return Author
     */
    public function addQuote(string $quote): self
    {
        $this->quotes->add(new Quote($this, $quote));

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getQuotes(): ArrayCollection
    {
        return $this->quotes;
    }
}