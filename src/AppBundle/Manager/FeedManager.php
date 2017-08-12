<?php declare(strict_types=1);

namespace AppBundle\Manager;

use AppBundle\Collection\FeedCollection;
use AppBundle\Entity\FeedEntry;
use AppBundle\Repository\FeedRepository;
use Doctrine\ORM\EntityManager;

/**
 * Manages data persistence for feed entries
 *
 * @see FeedEntry
 */
class FeedManager
{
    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    /**
     * @var FeedRepository $repository
     */
    private $repository;

    /**
     * @var FeedEntry[] $toPersist
     */
    private $toPersist = [];

    public function __construct(EntityManager $entityManager, FeedRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function push(FeedCollection $feeds)
    {
        foreach ($feeds as $feed) {
            if (!$this->repository->findByFeedId($feed->getNewsId())) {
                $this->persist($feed);
            }
        }

        $this->flush();
    }

    private function persist(FeedEntry $feed)
    {
        $this->entityManager->persist($feed);
        $this->toPersist[] = $feed;
    }

    private function flush(FeedEntry $feed = null)
    {
        $this->entityManager->flush($feed ?? $this->toPersist);
    }
}
