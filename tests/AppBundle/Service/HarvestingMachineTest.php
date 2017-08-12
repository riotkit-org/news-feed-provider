<?php declare(strict_types=1);

namespace Tests\AppBundle\Service;

use AppBundle\Entity\FeedSource;
use AppBundle\Entity\NewsBoard;
use AppBundle\Factory\Specification\RssSpecificationFactory;
use AppBundle\Manager\FeedManager;
use AppBundle\Repository\FeedRepository;
use AppBundle\Service\Collector\RssCollector;
use AppBundle\Service\HarvestingMachine;
use Doctrine\ORM\EntityManager;
use Tests\TestCase;

/**
 * @see HarvestingMachine
 */
class HarvestingMachineTest extends TestCase
{
    /**
     * @test
     */
    public function commits_data_when_single_entry()
    {
        $manager = $this->createManager();
        $machine = $this->createMachine($manager);

        $manager->expects($this->once())->method('push');

        $machine->setCollectors([
            new RssCollector(
                $this->createReader(),
                new RssSpecificationFactory()
            )
        ]);

        $machine->collect([
            FeedSource::create(
                new NewsBoard(),
                'rss',
                ['url' => 'file://' . $this->getTestFeedPath()],
                'pl'
            )
        ]);
    }

    /**
     * @test
     */
    public function commits_data_when_more_than_one_entry_was_collected()
    {
        $manager = $this->createManager();
        $machine = $this->createMachine($manager);

        $manager->expects($this->atLeast(5))->method('push');

        $machine->setCollectors([
            new RssCollector(
                $this->createReader(),
                new RssSpecificationFactory()
            )
        ]);

        $entries = [];

        for ($num = 0; $num <= 100; $num++) {
            $entries[] = FeedSource::create(
                new NewsBoard(),
                'rss',
                ['url' => 'file://' . $this->getTestFeedPath()],
                'pl'
            );
        }

        $machine->collect($entries);
    }

    /**
     * @test
     */
    public function does_not_commit_data_when_no_data_collected()
    {
        $manager = $this->createManager();
        $machine = $this->createMachine($manager);

        $manager->expects($this->never())->method('push');

        $machine->setCollectors([
            new RssCollector(
                $this->createReader(),
                new RssSpecificationFactory()
            )
        ]);

        $machine->collect([]);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|HarvestingMachine
     */
    private function createMachine($manager)
    {
        $builder = $this->getMockBuilder(HarvestingMachine::class);
        $builder->setConstructorArgs([$manager]);
        $builder->setMethods(['commit']);
        $machine = $builder->getMock();

        return $machine;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|FeedManager
     */
    private function createManager()
    {
        $builder = $this->getMockBuilder(FeedManager::class);
        $builder->setConstructorArgs([
            $this->createMock(EntityManager::class),
            $this->createMock(FeedRepository::class)
        ]);
        $builder->setMethods(['push']);

        return $builder->getMock();
    }
}
