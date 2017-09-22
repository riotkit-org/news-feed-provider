<?php declare(strict_types=1);

namespace AppBundle\Manager;

use AppBundle\Entity\FeedSource;
use AppBundle\Repository\FeedSourceRepository;
use Doctrine\ORM\EntityManager;

/**
 * @see FeedSource
 */
class FeedSourceManager
{
    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * @var FeedSourceRepository $repository
     */
    protected $repository;

    public function __construct(EntityManager $em, FeedSourceRepository $repository)
    {
        $this->entityManager = $em;
        $this->repository    = $repository;
    }

    public function persist(FeedSource $source)
    {
        $this->entityManager->persist($source);
    }

    public function remove(FeedSource $source)
    {
        $this->entityManager->remove($source);
    }

    public function getRepository() : FeedSourceRepository
    {
        return $this->repository;
    }

    public function flush(FeedSource $source = null)
    {
        $this->entityManager->flush($source);
    }
}
