<?php declare(strict_types=1);

namespace AppBundle\Entity;

class FeedEntry
{
    /**
     * @var string $newsId Id of external content, used to check i the news was already added
     */
    protected $newsId;

    /**
     * @var NewsBoard $newsBoard
     */
    protected $newsBoard;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var \DateTime $date News date (not the date when collected)
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
     * Creates an instance (allows to keep the entity immutable)
     *
     * @param string $newsId
     * @param NewsBoard $newsBoard
     * @param string $title
     * @param \DateTimeImmutable $date
     * @param \DateTimeImmutable $collectionDate
     * @param string $language
     *
     * @return FeedEntry
     */
    public static function create(
        string $newsId,
        NewsBoard $newsBoard,
        string $title,
        \DateTimeImmutable $date,
        \DateTimeImmutable $collectionDate,
        string $language
    ) : FeedEntry {

        $feed = new self();
        $feed->newsId = $newsId;
        $feed->newsBoard = $newsBoard;
        $feed->title = $title;
        $feed->date = $date;
        $feed->collectionDate = $collectionDate;
        $feed->language = $language;

        return $feed;
    }

    /**
     * @return string
     */
    public function getNewsId() : string
    {
        return $this->newsId;
    }

    /**
     * @return NewsBoard
     */
    public function getNewsBoard() : NewsBoard
    {
        return $this->newsBoard;
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate() : \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCollectionDate() : \DateTimeImmutable
    {
        return $this->collectionDate ?? new \DateTimeImmutable();
    }

    /**
     * @return string
     */
    public function getLanguage() : string
    {
        return $this->language ?? '';
    }

    /**
     * @return array
     */
    public function getTags() : array
    {
        return $this->tags;
    }
}
