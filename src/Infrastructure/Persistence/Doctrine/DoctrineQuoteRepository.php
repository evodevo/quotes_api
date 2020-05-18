<?php

declare(strict_types=1);

namespace QuotesAPI\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use QuotesAPI\Domain\Quote;
use QuotesAPI\Domain\QuoteRepository;

/**
 * Class DoctrineQuoteRepository
 * @package QuotesAPI\Infrastructure\Persistence\Doctrine
 */
class DoctrineQuoteRepository extends ServiceEntityRepository implements QuoteRepository
{
    /**
     * DoctrineAuthorRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quote::class);
    }

    /**
     * @param string $authorSlug
     * @param int $limit
     * @return array
     */
    public function findAllByAuthorSlug(string $authorSlug, int $limit = null): array
    {
        if (!$limit) {
            $limit = self::DEFAULT_QUOTES_LIMIT;
        }

        return $this->createQueryBuilder('quote')
            ->select('quote')
            ->leftJoin('quote.author', 'author')
            ->where('author.slug = :authorSlug')
            ->setMaxResults($limit)
            ->setParameter('authorSlug', $authorSlug)
            ->getQuery()->getResult();
    }

    /**
     * @param Quote $quote
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(Quote $quote)
    {
        $this->getEntityManager()->persist($quote);
        $this->getEntityManager()->flush();
    }
}