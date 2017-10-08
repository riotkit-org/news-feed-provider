<?php declare(strict_types=1);

namespace AppBundle\Form\Model;

use AppBundle\Entity\FeedEntry;

/**
 * @see FeedEntry
 */
class FeedEntrySearchCriteria implements SearchCriteriaInterface
{
    protected $newsId;
    protected $feedSource;
    protected $content;
    protected $sourceUrl;
    protected $dateFrom;
    protected $dateTo;
    protected $language;
    protected $tags;
    protected $newsBoard;
    protected $exceptFeedSource;

    public function __construct(array $input)
    {
        $this->newsId     = $input['newsId'] ?? [];
        $this->feedSource = $input['feedSource'] ?? [];
        $this->content    = $input['content'] ?? '';
        $this->sourceUrl  = $input['sourceUrl'] ?? [];
        $this->dateFrom   = $input['dateFrom'] ?? '';
        $this->dateTo     = $input['dateTo'] ?? '';
        $this->language   = $input['language'] ?? [];
        $this->tags       = $input['tags'] ?? [];
        $this->newsBoard  = $input['newsBoard'] ?? '';
        $this->exceptFeedSource = $input['exceptFeedSource'] ?? [];
    }

    public function getDefinition() : array
    {
        return [
            [
                'name' => 'newsId',
                'handler' => SearchCriteriaInterface::HANDLER_MULTIPLE_VALUE,
            ],
            [
                'name' => 'feedSource',
                'handler' => SearchCriteriaInterface::HANDLER_MULTIPLE_VALUE,
            ],
            [
                'name' => 'content',
                'handler' => SearchCriteriaInterface::HANDLER_CONTAINS_VALUE,
            ],
            [
                'name' => 'sourceUrl',
                'handler' => SearchCriteriaInterface::HANDLER_MULTIPLE_VALUE,
            ],
            [
                'name' => 'language',
                'handler' => SearchCriteriaInterface::HANDLER_MULTIPLE_VALUE,
            ],
            [
                'name' => 'tags',
                'handler' => SearchCriteriaInterface::HANDLER_MULTIPLE_VALUE,
            ],
            [
                'name' => 'dateFrom',
                'handler' => SearchCriteriaInterface::HANDLER_DATE_RANGE_FROM,
                'column' => 'date',
            ],
            [
                'name' => 'dateTo',
                'handler' => SearchCriteriaInterface::HANDLER_DATE_RANGE_TO,
                'column' => 'date',
            ],
            [
                'name' => 'newsBoard',
                'handler' => SearchCriteriaInterface::HANDLER_NONE,
            ],
            [
                'name'    => 'exceptFeedSource',
                'handler' => SearchCriteriaInterface::HANDLER_MULTIPLE_VALUE,
                'column'  => 'feedSource',
                'except'  => true,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getNewsId()
    {
        return (array) $this->newsId;
    }

    /**
     * @return array
     */
    public function getFeedSource()
    {
        return (array) $this->feedSource;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return (string) $this->content;
    }

    /**
     * @return array
     */
    public function getSourceUrl()
    {
        return (array) $this->sourceUrl;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateFrom()
    {
        return $this->dateFrom ? new \DateTime($this->dateFrom) : null;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateTo()
    {
        return $this->dateTo ? new \DateTime($this->dateTo) : null;
    }

    /**
     * @return array
     */
    public function getLanguage()
    {
        return (array) $this->language;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return (array) $this->tags;
    }

    /**
     * @return string
     */
    public function getNewsBoard(): string
    {
        return $this->newsBoard;
    }

    /**
     * @return string[]
     */
    public function getExceptFeedSource()
    {
        return $this->exceptFeedSource;
    }

    /**
     * @param string[] $exceptFeedSource
     * @return FeedEntrySearchCriteria
     */
    public function setExceptFeedSource($exceptFeedSource): FeedEntrySearchCriteria
    {
        $this->exceptFeedSource = $exceptFeedSource;
        return $this;
    }
}
