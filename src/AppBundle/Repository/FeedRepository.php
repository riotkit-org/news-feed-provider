<?php declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\FeedEntry;
use Doctrine\ORM\EntityRepository;

/**
 * @see FeedEntry
 */
class FeedRepository extends EntityRepository
{
    /**
     * Find entry by news id which is generated
     * by the collector (fully reproducible for deduplication usage)
     *
     * @param string $id
     * @return FeedEntry|null
     */
    public function findByFeedId(string $id)
    {
        $qb = $this->createQueryBuilder('f');
        $qb->where('f.news_id = :news_id');
        $qb->setParameter('news_id', $id);

        return $qb->getQuery()->getSingleResult();
    }
}
