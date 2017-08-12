<?php declare(strict_types=1);

namespace AppBundle\Service\Collector;

use AppBundle\Collection\FeedCollection;
use AppBundle\Entity\FeedEntry;
use AppBundle\Entity\FeedSource;
use AppBundle\Factory\Specification\RssSpecificationFactory;
use AppBundle\ValueObject\Specification\RssSourceSpecification;
use PicoFeed\Parser\Item;
use PicoFeed\Reader\Reader;
use DateTimeImmutable;

/**
 * RSS Collector
 * =============
 *   Collects articles from RSS sources using an external library
 */
class RssCollector implements CollectorInterface
{
    protected $reader;
    protected $specificationFactory;

    public function __construct(
        Reader $reader,
        RssSpecificationFactory $specificationFactory
    ) {
        $this->reader = $reader;
        $this->specificationFactory = $specificationFactory;
    }

    public function collect(FeedSource $source) : FeedCollection
    {
        $feeds = new FeedCollection();
        $parameters = $this->createParameters($source);
        $resource = $this->reader->download($parameters->getUrl());

        $parser = $this->reader->getParser(
            $resource->getUrl(),
            $resource->getContent(),
            $resource->getEncoding()
        );

        foreach ($parser->execute()->getItems() as $item) {
            $feeds->add($this->parseFeedItem($item, $source));
        }

        return $feeds;
    }

    public function isAbleToHandle(FeedSource $source) : bool
    {
        return $source->getCollectorName() === 'rss';
    }

    public static function getCollectorName() : string
    {
        return 'rss';
    }

    protected function parseFeedItem(
        Item $item,
        FeedSource $feedSource
    ) : FeedEntry {

        return FeedEntry::create(
            $this->getId($item),
            $feedSource->getNewsBoard(),
            $item->getTitle(),
            DateTimeImmutable::createFromMutable($item->getPublishedDate()),
            new DateTimeImmutable('now'),
            $item->getLanguage() ? $item->getLanguage() : $feedSource->getDefaultLanguage()
        );
    }

    protected function getId(Item $item) : string
    {
        $url = $item->getUrl();
        $guid = $item->getTag('guid', 'isPermaLink=true');

        if (count($guid) > 0) {
            return hash('sha256', $guid[0]);
        }

        return hash('sha256', $url);
    }

    protected function createParameters(FeedSource $source) : RssSourceSpecification
    {
        return $this->specificationFactory->create($source->getSourceSpecification());
    }
}
