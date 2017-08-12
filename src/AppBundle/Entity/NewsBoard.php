<?php declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\PersistentCollection;

class NewsBoard
{
    /**
     * @var string $id UUID-4
     */
    protected $id;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $description
     */
    protected $description;

    /**
     * @var PersistentCollection|FeedSource[] $feedSources
     */
    protected $feedSources;

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id ?? '';
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name ?? '';
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description ?? '';
    }

    /**
     * @return FeedSource[]|PersistentCollection
     */
    public function getFeedSources()
    {
        return $this->feedSources;
    }
}
