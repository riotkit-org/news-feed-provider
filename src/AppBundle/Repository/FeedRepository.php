<?php declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Collection\FeedCollection;
use AppBundle\Collection\PaginatedFeedCollection;
use AppBundle\Entity\FeedEntry;
use AppBundle\Form\Model\FeedEntrySearchCriteria;
use AppBundle\ValueObject\PaginationInformation;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * @see FeedEntry
 */
class FeedRepository extends EntityRepository
{
    use SearchCriteriaSupporting;

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

    /**
     * Search using a search criteria
     *
     * @param FeedEntrySearchCriteria $criteria
     * @return PaginatedFeedCollection
     */
    public function findBySearchCriteria(FeedEntrySearchCriteria $criteria, int $page = 1, int $limit = 20)
    {
        $qb = $this->createQueryBuilder('f');
        $this->applyCriteriaToQueryBuilder($criteria, $qb, 'f');

        $qb->addOrderBy('f.date', 'DESC');
        $qb->addOrderBy('f.title', 'ASC');
        $qb->addOrderBy('f.newsId', 'DESC');

        // count total results
        $countingQuery = clone $qb;
        $countingQuery->select('count(f) as total');
        $maxResults = $countingQuery->getQuery()->getResult()[0]['total'];

        // pagination
        $position = ($page - 1) * $limit;
        $qb->setFirstResult($position);
        $qb->setMaxResults($limit);

        $results = $qb->getQuery()->getResult();

        return new PaginatedFeedCollection(
            new FeedCollection($results),
            new PaginationInformation([
                'current_position' => (int) $position,
                'returned_results' => count($results),
                'total_results'    => (int) $maxResults,
                'current_page'     => (int) $page,
                'max_pages'        => (int) ceil($maxResults / $limit),
            ])
        );
    }
}
