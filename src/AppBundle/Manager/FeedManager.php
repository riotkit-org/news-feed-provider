<?php declare(strict_types=1);

namespace AppBundle\Manager;

use AppBundle\AppEvents;
use AppBundle\Collection\FeedCollection;
use AppBundle\Entity\FeedEntry;
use AppBundle\Repository\FeedRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

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
     * @var FeedEntry[] $toFlush
     */
    private $toFlush = [];

    /**
     * @var EventDispatcherInterface $dispatcher
     */
    private $dispatcher;

    public function __construct(EntityManager $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $em;
        $this->repository = $em->getRepository(FeedEntry::class);
        $this->dispatcher = $eventDispatcher;
    }

    /**
     * Push entries to the database
     * if they do not exists yet
     *
     * @param FeedCollection $feeds
     */
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
        $this->dispatcher->dispatch(AppEvents::FEED_PRE_PERSIST, new GenericEvent($feed));

        $this->entityManager->persist($feed);
        $this->toFlush[] = $feed;
    }

    private function flush(FeedEntry $feed = null)
    {
        $this->entityManager->flush($feed ?? $this->toFlush);
    }

    /**
     * @return FeedRepository
     */
    public function getRepository(): FeedRepository
    {
        return $this->repository;
    }
}
