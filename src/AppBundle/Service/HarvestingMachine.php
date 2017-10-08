<?php declare(strict_types=1);

namespace AppBundle\Service;

use AppBundle\Collection\CollectorCollection;
use AppBundle\Collection\FeedCollection;
use AppBundle\Entity\FeedSource;
use AppBundle\Manager\FeedManager;
use AppBundle\Service\Collector\CollectorInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Collects data from everywhere using collectors
 * and passes data to persistence layer
 */
class HarvestingMachine
{
    const MAX_ENTRIES_BUFFER_SIZE = 50;

    /**
     * @var CollectorCollection $collectors
     */
    protected $collectors;

    /**
     * @var FeedManager $manager
     */
    protected $manager;

    /**
     * @var Logger $logger
     */
    protected $logger;

    public function __construct(FeedManager $manager, Logger $logger)
    {
        $this->manager = $manager;
        $this->logger  = $logger;
    }

    /**
     * @param FeedSource[] $feedSources
     */
    public function collect(array $feedSources = [], OutputInterface $output = null)
    {
        $this->logCurrentState($output, 'Got ' . count($feedSources) . ' feed sources to collect');
        $feeds = new FeedCollection();

        foreach ($feedSources as $feedSource) {
            $collector = $this->collectors->findByName($feedSource->getCollectorName());

            try {
                $feeds = $feeds->concat($collector->collect($feedSource));

            } catch (\Exception $exception) {
                $this->logCurrentState(
                    $output,
                    'An exception occurred while collecting data from ' . $feedSource .
                    ', details: ' . $exception->getMessage(),
                    [(string) $collector, (string) $feedSource, $exception]
                );

                continue;
            }

            $this->logCurrentState($output, 'Buffer size is ' . $feeds->count(), [(string) $collector, (string) $feedSource]);

            // commit every X entries in a transaction
            $this->commit($feeds, false);
            $this->logCurrentState($output, 'Commiting ' . $feeds->count() . ' feeds, buffer max size: ' . self::MAX_ENTRIES_BUFFER_SIZE);
        }

        if (!$feeds->isEmpty()) {
            $this->commit($feeds, true);
            $this->logCurrentState($output, 'Finishing, remaining ' . $feeds->count() . ' elements');
        }
    }

    public function getCollectors() : CollectorCollection
    {
        return $this->collectors;
    }

    /**
     * @param CollectorInterface[] $collectors
     * @throws \LogicException
     */
    public function setCollectors(array $collectors)
    {
        if ($this->collectors !== null) {
            throw new \LogicException('Collectors cannot be injected twice');
        }

        $this->collectors = new CollectorCollection($collectors);
    }

    protected function logCurrentState(
        OutputInterface $output = null,
        string $message,
        array $contextObjects = []
    ) {
        if ($output === null) {
            return;
        }

        $message = '[' . date('Y-m-d H:i:s') . '] ' . $message;

        $this->logger->info($message, $contextObjects);
    }

    protected function commit(FeedCollection $feeds, bool $force)
    {
        if ($force === true || $feeds->count() >= self::MAX_ENTRIES_BUFFER_SIZE) {
            $this->manager->push($feeds);
            $feeds->clear();
        }
    }
}
