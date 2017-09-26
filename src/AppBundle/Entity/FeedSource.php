<?php declare(strict_types=1);

namespace AppBundle\Entity;

class FeedSource implements EntityInterface
{
    /**
     * @var string $id UUID-4
     */
    protected $id;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var string $description
     */
    protected $description;

    /**
     * @var NewsBoard $newsBoard
     */
    protected $newsBoard;

    /**
     * @var string $collectorName
     */
    protected $collectorName;

    /**
     * Contains details about the source
     * eg. the url address
     *
     * Stored in JSON format
     *
     * @var array $sourceData
     */
    protected $sourceData = [];

    /**
     * @var string $defaultLanguage
     */
    protected $defaultLanguage;

    /**
     * @var \DateTimeImmutable $lastCollectionDate
     */
    protected $lastCollectionDate;

    /**
     * @var bool $enabled
     */
    protected $enabled;

    public static function create(
        NewsBoard $board,
        string $collectorName,
        array $sourceData,
        string $defaultLanguage,
        \DateTimeImmutable $lastCollectionDate,
        bool $enabled,
        string $title,
        string $description
    ) : FeedSource {

        $feed = new self();
        $feed->newsBoard = $board;
        $feed->collectorName = $collectorName;
        $feed->sourceData = $sourceData;
        $feed->defaultLanguage = $defaultLanguage;
        $feed->lastCollectionDate = $lastCollectionDate;
        $feed->enabled = $enabled;
        $feed->title = $title;
        $feed->description = $description;

        return $feed;
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id ?? '';
    }

    /**
     * @return NewsBoard
     */
    public function getNewsBoard()
    {
        return $this->newsBoard;
    }

    public function getCollectorName() : string
    {
        return $this->collectorName ?? '';
    }

    public function getSourceSpecification() : array
    {
        return $this->sourceData ?? [];
    }

    public function getDefaultLanguage() : string
    {
        return $this->defaultLanguage ?? '';
    }

    public function getSourceData() : array
    {
        return $this->sourceData;
    }

    public function setCollectorName($collectorName) : FeedSource
    {
        $this->collectorName = $collectorName;
        return $this;
    }

    public function setNewsBoard(NewsBoard $newsBoard) : FeedSource
    {
        $this->newsBoard = $newsBoard;
        return $this;
    }

    public function setSourceData($sourceData) : FeedSource
    {
        $this->sourceData = $sourceData;
        return $this;
    }

    public function setDefaultLanguage(string $defaultLanguage) : FeedSource
    {
        $this->defaultLanguage = $defaultLanguage;
        return $this;
    }

    public static function getPublicTypeName() : string
    {
        return 'feedsource';
    }

    public function jsonSerialize()
    {
        return [
            'id'                   => $this->getId(),
            NewsBoard::getPublicTypeName() => $this->getNewsBoard() ? $this->getNewsBoard()->getId() : null,
            'title'                => $this->getTitle(),
            'description'          => $this->getDescription(),
            'source_data'          => $this->getSourceSpecification(),
            'collector_name'       => $this->getCollectorName(),
            'default_language'     => $this->getDefaultLanguage(),
            'last_collection_date' => $this->getLastCollectionDate()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRelations(): array
    {
        return [
            NewsBoard::getPublicTypeName() => [
                $this->getNewsBoard()->getId() => $this->getNewsBoard(),
            ],
        ];
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getLastCollectionDate()
    {
        return $this->lastCollectionDate ?? new \DateTime('1980-01-01');
    }

    public function isEnabled() : bool
    {
        return $this->enabled ?? false;
    }

    public function __toString() : string
    {
        return 'FeedSource:' . $this->getId();
    }

    public function getTitle() : string
    {
        return $this->title ?? '';
    }

    public function getDescription() : string
    {
        return $this->description ?? '';
    }

    public function setEnabled(bool $enabled) : FeedSource
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function setTitle(string $title) : FeedSource
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description) : FeedSource
    {
        $this->description = $description;
        return $this;
    }
}
