<?php declare(strict_types=1);

namespace AppBundle\Entity;

class FeedSource
{
    /**
     * @var string $id UUID-4
     */
    protected $id;

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
    protected $sourceData;

    /**
     * @var string $defaultLanguage
     */
    protected $defaultLanguage;

    public static function create(
        NewsBoard $board,
        string $collectorName,
        array $sourceData,
        string $defaultLanguage
    ) : FeedSource {

        $feed = new self();
        $feed->newsBoard = $board;
        $feed->collectorName = $collectorName;
        $feed->sourceData = $sourceData;
        $feed->defaultLanguage = $defaultLanguage;

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
    public function getNewsBoard() : NewsBoard
    {
        return $this->newsBoard;
    }

    /**
     * @return string
     */
    public function getCollectorName() : string
    {
        return $this->collectorName ?? '';
    }

    /**
     * @return array
     */
    public function getSourceSpecification() : array
    {
        return $this->sourceData ?? [];
    }

    /**
     * @return string
     */
    public function getDefaultLanguage() : string
    {
        return $this->defaultLanguage ?? '';
    }
}
