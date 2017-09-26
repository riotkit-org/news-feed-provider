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

    public function __construct(array $input)
    {
        $this->id              = $input['id'] ?? [];
        $this->newsBoardId     = $input['newsBoardId'] ?? [];
        $this->collectorName   = $input['collectorName'] ?? [];
        $this->title           = $input['title'] ?? '';
        $this->description     = $input['description'] ?? '';
        $this->defaultLanguage = $input['defaultLanguage'] ?? '';
        $this->enabled         = $input['enabled'] ?? [true];
    }

    public function getDefinition() : array
    {
        return [
            [
                'name' => 'enabled',
                'handler' => SearchCriteriaInterface::HANDLER_MULTIPLE_VALUE,
            ],
            [
                'name' => 'description',
                'handler' => SearchCriteriaInterface::HANDLER_CONTAINS_VALUE,
            ],
            [
                'name' => 'title',
                'handler' => SearchCriteriaInterface::HANDLER_CONTAINS_VALUE,
            ],
            [
                'name' => 'newsBoardId',
                'handler' => SearchCriteriaInterface::HANDLER_MULTIPLE_VALUE,
            ],
            [
                'name' => 'collectorName',
                'handler' => SearchCriteriaInterface::HANDLER_MULTIPLE_VALUE,
            ],
            [
                'name' => 'description',
                'handler' => SearchCriteriaInterface::HANDLER_CONTAINS_VALUE,
            ],
            [
                'name' => 'defaultLanguage',
                'handler' => SearchCriteriaInterface::HANDLER_MULTIPLE_VALUE,
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

    /**
     * @return bool[]
     */
    public function isEnabled() : array
    {
        return $this->enabled;
    }
}
