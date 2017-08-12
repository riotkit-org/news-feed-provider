<?php declare(strict_types=1);

namespace AppBundle\Service;

use AppBundle\Collection\CollectorCollection;
use AppBundle\Collection\FeedCollection;
use AppBundle\Entity\FeedSource;
use AppBundle\Manager\FeedManager;

/**
 * Collects data from everywhere using collectors
 * and passes data to persistence layer
 */
class HarvestingMachine
{
    const ENTRIES_BUFFER = 50;

    /**
     * @var CollectorCollection $collectors
     */
    private $collectors;

    /**
     * @var FeedManager $manager
     */
    private $manager;

    public function __construct(FeedManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param FeedSource[] $feedSources
     */
    public function collect(array $feedSources = [])
    {
        $feeds = new FeedCollection();

        foreach ($feedSources as $feedSource) {
            $collector = $this->collectors->findByName($feedSource->getCollectorName());

            $feeds = $feeds->concat($collector->collect($feedSource));

            // commit every X entries in a transaction
            $this->commit($feeds, false);
        }

        if (!$feeds->isEmpty()) {
            $this->commit($feeds, true);
        }
    }

    public function setCollectors(array $collectors)
    {
        $this->collectors = new CollectorCollection($collectors);
    }

    private function commit(FeedCollection $feeds, bool $force)
    {
        if ($force === true || $feeds->count() >= self::ENTRIES_BUFFER) {
            $this->manager->push($feeds);
            $feeds->clear();
        }
    }
}
