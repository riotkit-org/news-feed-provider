<?php declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\FeedEntry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

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
        $qb->where('f.newsId = :news_id');
        $qb->setParameter('news_id', $id);

        try {
            return $qb->getQuery()->getSingleResult();

        } catch (NoResultException $exception) {
            return null;
        }
    }
}
