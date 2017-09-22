<?php declare(strict_types=1);

namespace AppBundle\Form\Model;

/**
 * @see FeedSource
 */
class FeedSourceSearchCriteria implements SearchCriteriaInterface
{
    /**
     * @var string[] $id
     */
    protected $id;

    /**
     * @var string[] $newsBoardId
     */
    protected $newsBoardId;

    /**
     * @var string[] $collectorName
     */
    protected $collectorName;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var string $description
     */
    protected $description;

    /**
     * @var string[] $defaultLanguage
     */
    protected $defaultLanguage;

    /**
     * @var bool[] $enabled
     */
    protected $enabled = [true];

    public function getDefinition() : array
    {
        return [
            [
                'name' => 'enabled',
                'handler' => 'multiple-value',
            ],
            [
                'name' => 'description',
                'handler' => 'contains-value',
            ],
        ];
    }

    /**
     * @return \string[]
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \string[]
     */
    public function getNewsBoardId()
    {
        return $this->newsBoardId;
    }

    /**
     * @return \string[]
     */
    public function getCollectorName()
    {
        return $this->collectorName;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return \string[]
     */
    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }
}
