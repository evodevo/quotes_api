<?php

declare(strict_types=1);

namespace QuotesAPI\Domain;

/**
 * Class Quote
 * @package QuotesAPI\Domain
 */
class Quote
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var Author
     */
    protected $author;

    /**
     * @var string
     */
    protected $text;

    /**
     * Quote constructor.
     * @param Author $author
     * @param string $text
     * @param int|null $id
     */
    public function __construct(Author $author, string $text, int $id = null)
    {
        $this->id = $id;
        $this->author = $author;
        $this->text = $text;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Quote
     */
    public function shout(): self
    {
        $this->text = rtrim(mb_strtoupper($this->text), '.!') . '!';

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}