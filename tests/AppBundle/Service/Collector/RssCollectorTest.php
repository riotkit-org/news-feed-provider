<?php declare(strict_types=1);

namespace Tests\AppBundle\Service\Collector;

use AppBundle\Entity\FeedSource;
use AppBundle\Entity\NewsBoard;
use AppBundle\Factory\Specification\RssSpecificationFactory;
use AppBundle\Service\Collector\RssCollector;
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
        $collector = new RssCollector($this->createReader(), new RssSpecificationFactory());
        $feeds = $collector->collect(
            FeedSource::create(
                new NewsBoard(),
                'rss',
                ['url' => 'file://' . $this->getTestFeedPath()],
                'pl'
            )
        );

        $this->assertNotEmpty($feeds->toArray());

        foreach ($feeds->toArray() as $feed) {
            $this->assertNotEmpty($feed->getTitle());
            $this->assertNotEmpty($feed->getLanguage());
        }
    }
}
