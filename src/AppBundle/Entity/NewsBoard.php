<?php declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\PersistentCollection;

class NewsBoard implements EntityInterface
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

    public static function create(
        string $name,
        string $description
    ) : NewsBoard {

        $board = new self();
        $board->name = $name;
        $board->description = $description;

        return $board;
    }

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

    public function setName(string $name): NewsBoard
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription(string $description): NewsBoard
    {
        $this->description = $description;
        return $this;
    }

    public function setId(string $id): NewsBoard
    {
        $this->id = $id;
        return $this;
    }

    public static function getPublicTypeName(): string
    {
        return 'newsboard';
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
        ];
    }

    public function getRelations(): array
    {
        $feedSourcesIndexed = [];

        foreach ($this->getFeedSources() as $feedSource) {
            $feedSourcesIndexed[$feedSource->getId()] = $feedSource;
        }

        return [
            FeedSource::getPublicTypeName() => $feedSourcesIndexed,
        ];
    }
}
