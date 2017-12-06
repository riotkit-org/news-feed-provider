<?php declare(strict_types=1);

namespace Tests\AppBundle\Service;

use AppBundle\Entity\FeedSource;
use AppBundle\Entity\NewsBoard;
use AppBundle\Service\Spider\WebSpider;
use GuzzleHttp\Psr7\Response;
use RssSupportBundle\Factory\Specification\RssSpecificationFactory;
use AppBundle\Manager\FeedManager;
use RssSupportBundle\Service\Collector\RssCollector;
use AppBundle\Service\HarvestingMachine;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcher;
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
        $machine = $machine = $this->createMachine($manager, function (\PHPUnit_Framework_MockObject_MockBuilder $builder) {
            $builder->setMethods(['logCurrentState']);
        });
        $manager->expects($this->once())->method('push');

        $machine->setCollectors([
            new RssCollector(
                $this->createReader(),
                new RssSpecificationFactory(),
                new WebSpider($this->getMockedWebClient(true))
            )
        ]);

        $machine->collect([
            FeedSource::create(
                new NewsBoard(),
                'rss',
                ['url' => 'file://' . $this->getTestFeedPath()],
                'pl',
                new \DateTimeImmutable('now'),
                true,
                'Test',
                ''
            )
        ]);
    }

    /**
     * @test
     */
    public function commits_data_when_more_than_one_entry_was_collected()
    {
        $manager = $this->createManager();
        $machine = $machine = $this->createMachine($manager, function (\PHPUnit_Framework_MockObject_MockBuilder $builder) {
            $builder->setMethods(['logCurrentState']);
        });
        $mockedClient = $this->getMockedWebClient();

        $manager->expects($this->atLeast(1))->method('push');

        $machine->setCollectors([
            new RssCollector(
                $this->createReader(),
                new RssSpecificationFactory(),
                new WebSpider($mockedClient)
            )
        ]);

        $entries = [];

        for ($num = 0; $num <= 100; $num++) {
            $entries[] = FeedSource::create(
                new NewsBoard(),
                'rss',
                ['url' => 'file://' . $this->getTestFeedPath()],
                'pl',
                new \DateTimeImmutable('now'),
                true,
                'Test',
                ''
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
        $machine = $machine = $this->createMachine($manager);

        $manager->expects($this->never())->method('push');

        $machine->setCollectors([
            new RssCollector(
                $this->createReader(),
                new RssSpecificationFactory(),
                new WebSpider($this->getMockedWebClient())
            )
        ]);

        $machine->collect([]);
    }

    /**
     * @param mixed $manager
     * @param callable $preparation
     * @return \PHPUnit_Framework_MockObject_MockObject|HarvestingMachine
     */
    private function createMachine($manager, callable $preparation = null)
    {
        $builder = $this->getMockBuilder(HarvestingMachine::class);
        $builder->setConstructorArgs([$manager, $this->createMock(Logger::class)]);

        if ($preparation !== null) {
            $preparation($builder);
        }

        return $builder->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|FeedManager
     */
    private function createManager()
    {
        $builder = $this->getMockBuilder(FeedManager::class);
        $builder->setConstructorArgs([
            $this->createMock(EntityManager::class),
            $this->createMock(EventDispatcher::class)
        ]);
        $builder->setMethods(['push']);

        return $builder->getMock();
    }
}
