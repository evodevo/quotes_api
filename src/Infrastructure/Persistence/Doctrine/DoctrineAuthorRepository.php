<?php

declare(strict_types=1);

namespace QuotesAPI\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use QuotesAPI\Domain\Author;
use QuotesAPI\Domain\AuthorRepository;

/**
 * Class DoctrineAuthorRepository
 * @package QuotesAPI\Infrastructure\Persistence\Doctrine
 */
class DoctrineAuthorRepository extends ServiceEntityRepository implements AuthorRepository
{
    /**
     * DoctrineAuthorRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * @param string $slug
     * @return Author|null
     */
    public function findBySlug(string $slug): ?Author
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    /**
     * @param Author $author
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(Author $author)
    {
        $this->getEntityManager()->persist($author);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Author $author
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Author $author)
    {
        $this->getEntityManager()->remove($author);
        $this->getEntityManager()->flush();
    }
}