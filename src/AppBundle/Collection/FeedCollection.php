<?php declare(strict_types=1);

namespace AppBundle\Collection;

use AppBundle\Entity\FeedEntry;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @method FeedEntry first()
 * @method FeedEntry[] toArray()
 * @method FeedEntry last()
 */
class FeedCollection extends ArrayCollection
{
    public function concat(FeedCollection $secondCollection) : FeedCollection
    {
        return new self(array_merge($this->toArray(), $secondCollection->toArray()));
    }
}
