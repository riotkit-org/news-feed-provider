<?php declare(strict_types=1);

namespace AppBundle\Entity;

/**
 * Represents a entry eg. a news, article
 */
class FeedEntry implements EntityInterface
{
    /**
     * @var string $newsId Id of external content, used to check if the news was already added
     */
    protected $newsId;

    /**
     * @var FeedSource $feedSource
     */
    protected $feedSource;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var string $content
     */
    protected $content;

    /**
     * @var string $fullContent
     */
    protected $fullContent;

    /**
     * @var string $sourceUrl
     */
    protected $sourceUrl;

    /**
     * @var \DateTimeImmutable $date News date (not the date when collected)
     */
    protected $date;

    /**
     * @var \DateTimeImmutable $collectionDate When the news was fetched
     */
    protected $collectionDate;

    /**
     * @var string $language
     */
    protected $language;

    /**
     * @var array $tags
     */
    protected $tags = [];

    /**
     * @var string $icon
     */
    protected $icon = '';

    /**
     * Creates an instance (allows to keep the entity immutable)
     *
     * @param array $attributes
     * @return FeedEntry
     */
    public static function create(array $attributes) : FeedEntry
    {
        $feed = new self();

        foreach ($attributes as $attributeName => $value) {
            $methodName = 'set' . ucfirst($attributeName);

            if (!method_exists($feed, $methodName)) {
                throw new \InvalidArgumentException('"' . $attributeName . '" is not a valid attribute name');
            }

            $feed->$methodName($value);
        }

        return $feed;
    }

    public function getNewsId() : string
    {
        return $this->newsId;
    }

    public function getFeedSource() : FeedSource
    {
        return $this->feedSource;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getDate() : \DateTimeImmutable
    {
        return $this->date;
    }

    public function getCollectionDate() : \DateTimeImmutable
    {
        return $this->collectionDate ?? new \DateTimeImmutable();
    }

    public function getLanguage() : string
    {
        return $this->language ?? '';
    }

    public function getTags() : array
    {
        return $this->tags;
    }

    protected function setFeedSource($feedSource) : FeedEntry
    {
        $this->feedSource = $feedSource;
        return $this;
    }

    public function setTitle($title) : FeedEntry
    {
        $this->title = $title;
        return $this;
    }

    public function setDate($date) : FeedEntry
    {
        $this->date = $date;
        return $this;
    }

    public function setCollectionDate($collectionDate) : FeedEntry
    {
        $this->collectionDate = $collectionDate;
        return $this;
    }

    public function setLanguage($language) : FeedEntry
    {
        $this->language = $language;
        return $this;
    }

    public function setTags($tags) : FeedEntry
    {
        $this->tags = $tags;
        return $this;
    }

    public function setNewsId($newsId) : FeedEntry
    {
        $this->newsId = $newsId;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content) : FeedEntry
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSourceUrl()
    {
        return $this->sourceUrl;
    }

    public function setSourceUrl($sourceUrl) : FeedEntry
    {
        $this->sourceUrl = $sourceUrl;
        return $this;
    }

    public function __toString(): string
    {
        return 'FeedEntry:' . $this->getNewsId();
    }

    public function getId(): string
    {
        return $this->getNewsId();
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return FeedEntry
     */
    public function setIcon(string $icon): FeedEntry
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullContent(): string
    {
        return $this->fullContent ?? '';
    }

    /**
     * @return bool
     */
    public function hasFullContent(): bool
    {
        return strlen($this->getFullContent()) > 0;
    }

    /**
     * @param string $fullContent
     * @return FeedEntry
     */
    public function setFullContent(string $fullContent): FeedEntry
    {
        $this->fullContent = $fullContent;
        return $this;
    }

    public static function getPublicTypeName(): string
    {
        return 'feed';
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'news_id'         => $this->getNewsId(),
            'title'           => $this->getTitle(),
            'content'         => $this->getContent(),
            'fullContent'     => $this->getFullContent(),
            'collection_date' => $this->getCollectionDate()->format('Y-m-d H:i:s'),
            'date'            => $this->getDate()->format('Y-m-d H:i:s'),
            'language'        => $this->getLanguage(),
            FeedSource::getPublicTypeName() => $this->getFeedSource()->getId(),
            'tags'            => $this->getTags(),
            'icon'            => $this->getIcon(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRelations(): array
    {
        return [
            FeedSource::getPublicTypeName() => [
                $this->getFeedSource()->getId() => $this->getFeedSource(),
            ],
        ];
    }
}
