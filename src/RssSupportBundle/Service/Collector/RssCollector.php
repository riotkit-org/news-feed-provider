<?php declare(strict_types=1);

namespace RssSupportBundle\Service\Collector;

use AppBundle\Collection\FeedCollection;
use AppBundle\Entity\FeedEntry;
use AppBundle\Entity\FeedSource;
use AppBundle\Exception\DuplicatedDataException;
use AppBundle\Service\Spider\WebSpider;
use RssSupportBundle\Factory\Specification\RssSpecificationFactory;
use AppBundle\Service\Collector\CollectorInterface;
use RssSupportBundle\ValueObject\Specification\RssSourceSpecification;
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
    /**
     * @var Reader $reader
     */
    protected $reader;

    /**
     * @var RssSpecificationFactory $specificationFactory
     */
    protected $specificationFactory;

    /**
     * @var WebSpider $webSpider
     */
    protected $webSpider;

    public function __construct(
        Reader $reader,
        RssSpecificationFactory $specificationFactory,
        WebSpider $spider
    ) {
        $this->reader = $reader;
        $this->specificationFactory = $specificationFactory;
        $this->webSpider = $spider;
    }

    /**
     * @see CollectorInterface::collect()
     * @param FeedSource $source
     *
     * @throws \PicoFeed\Parser\MalformedXmlException
     * @throws \PicoFeed\Reader\UnsupportedFeedFormatException
     *
     * @return FeedCollection
     */
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
            try {
                $feeds->add($this->createFeedEntryFromRssEntry($item, $source));

            } catch (DuplicatedDataException $exception) {
                // pass
            }
        }

        return $feeds;
    }

    /**
     * Answers a question if the collector is able to handle specified FeedSource
     *
     * @param FeedSource $source
     * @return bool
     */
    public function isAbleToHandle(FeedSource $source): bool
    {
        return $source->getCollectorName() === $this::getCollectorName();
    }

    public static function getCollectorName(): string
    {
        return 'rss';
    }

    public function __toString(): string
    {
        return 'Collector:' . self::getCollectorName();
    }

    protected function createFeedEntryFromRssEntry(
        Item $item,
        FeedSource $feedSource
    ) : FeedEntry {
        return FeedEntry::create([
            'newsId'         => $this->getId($item),
            'feedSource'     => $feedSource,
            'title'          => $this->correctEncoding($item->getTitle()),
            'content'        => $this->correctEncoding($item->getContent()),
            'sourceUrl'      => $item->getUrl(),
            'date'           => DateTimeImmutable::createFromMutable($item->getPublishedDate()),
            'collectionDate' => new DateTimeImmutable('now'),
            'language'       => $item->getLanguage() ? $this->correctEncoding($item->getLanguage()) : $feedSource->getDefaultLanguage(),
            'icon'           => $this->findIcon($item, $feedSource),
        ]);
    }

    private function correctEncoding(string $input)
    {
        return mb_convert_encoding($input, 'UTF-8', 'UTF-8');
    }

    protected function getId(Item $item): string
    {
        $url = $item->getUrl();
        $guid = $item->getTag('guid', 'isPermaLink=true');

        if (count($guid) > 0) {
            return hash('sha256', $guid[0]);
        }

        return hash('sha256', $url);
    }

    protected function createParameters(FeedSource $source): RssSourceSpecification
    {
        return $this->specificationFactory->create($source->getSourceSpecification());
    }

    /**
     * Find a icon for the entry
     *
     * @param Item $item
     * @param FeedSource $source
     *
     * @return string
     */
    protected function findIcon(Item $item, FeedSource $source): string
    {
        $spider = $this->webSpider->open($item->getUrl());
        $itemImage = $spider->findPageMainImage();

        if (!$itemImage) {
            return $source->getIcon();
        }

        return $itemImage;
    }
}
