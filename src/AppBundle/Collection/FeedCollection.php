<?php declare(strict_types=1);

namespace AppBundle\Collection;

use AppBundle\Entity\FeedEntry;
use AppBundle\Exception\DuplicatedDataException;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @method FeedEntry first()
 * @method FeedEntry[] toArray()
 * @method FeedEntry last()
 */
class FeedCollection extends ArrayCollection
{
    protected $idList = [];

    public function __construct(array $elements = [])
    {
        $elements = array_unique($elements);
        parent::__construct($elements);
    }

    /**
     * @throws DuplicatedDataException
     *
     * @param FeedEntry $element
     * @return bool
     */
    public function add($element)
    {
        if ($this->containsFeedEntry($element)) {
            throw new DuplicatedDataException('The element is already in the collection');
        }

        $result = parent::add($element);
        $this->rebuildIdList();

        return $result;
    }

    public function concat(FeedCollection $secondCollection) : FeedCollection
    {
        return new self(array_merge($this->toArray(), $secondCollection->toArray()));
    }

    private function containsFeedEntry(FeedEntry $entry) : bool
    {
        return in_array($entry->getNewsId(), $this->idList);
    }

    private function rebuildIdList()
    {
        $this->idList = array_map(
            function (FeedEntry $feed) {
                return $feed->getNewsId();
            },
            $this->toArray()
        );
    }
}
