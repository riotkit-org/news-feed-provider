<?php declare(strict_types=1);

namespace Tests\AppBundle\Service\Collector;

use AppBundle\Entity\FeedSource;
use AppBundle\Entity\NewsBoard;
use AppBundle\Service\Spider\WebSpider;
use RssSupportBundle\Factory\Specification\RssSpecificationFactory;
use RssSupportBundle\Service\Collector\RssCollector;
use Tests\TestCase;

/**
 * @see RssCollector
 */
class RssCollectorTest extends TestCase
{
    /**
     * @test
     */
    public function collects_at_least_one_entry()
    {
        $collector = new RssCollector(
            $this->createReader(),
            new RssSpecificationFactory(),
            new WebSpider(
                $this->getMockedWebClient()
            )
        );
        $feeds = $collector->collect(
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
        );

        $this->assertNotEmpty($feeds->toArray());

        foreach ($feeds->toArray() as $feed) {
            $this->assertNotEmpty($feed->getTitle());
            $this->assertNotEmpty($feed->getLanguage());
        }
    }
}
