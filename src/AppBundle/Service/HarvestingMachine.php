<?php declare(strict_types=1);

namespace AppBundle\Service;

use AppBundle\Collection\CollectorCollection;
use AppBundle\Collection\FeedCollection;
use AppBundle\Entity\FeedSource;
use AppBundle\Manager\FeedManager;
use AppBundle\Service\Collector\CollectorInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
                    [$collector, $feedSource]
                );

                continue;
            }

            $this->logCurrentState($output, 'Buffer size is ' . $feeds->count(), [$collector, $feedSource]);

            // commit every X entries in a transaction
            $this->commit($feeds, false);
        }

        if (!$feeds->isEmpty()) {
            $this->logCurrentState($output, 'Finishing, remaining ' . $feeds->count() . ' elements');
            $this->commit($feeds, true);
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

    private function logCurrentState(
        OutputInterface $output = null,
        string $message,
        array $contextObjects = []
    ) {
        if ($output === null) {
            return;
        }

        if (count($contextObjects) > 0) {
            $message = '[' . implode('] [', $contextObjects) . '] ' . $message;
        }

        $message = '[' . date('Y-m-d H:i:s') . '] ' . $message;

        $output->writeln($message);
    }

    private function commit(FeedCollection $feeds, bool $force)
    {
        if ($force === true || $feeds->count() >= self::ENTRIES_BUFFER) {
            $this->manager->push($feeds);
            $feeds->clear();
        }
    }
}
