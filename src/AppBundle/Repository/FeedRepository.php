<?php declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Collection\FeedCollection;
use AppBundle\Collection\PaginatedFeedCollection;
use AppBundle\Entity\FeedEntry;
use AppBundle\Form\Model\FeedEntrySearchCriteria;
use AppBundle\ValueObject\Feed\Timeline\Month;
use AppBundle\ValueObject\PaginationInformation;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

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
     * @param int $page
     * @param int $limit
     *
     * @return PaginatedFeedCollection
     */
    public function findBySearchCriteria(FeedEntrySearchCriteria $criteria, int $page = 1, int $limit = 20): PaginatedFeedCollection
    {
        $qb = $this->createCriteriaQueryBuilder($criteria);

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

    /**
     * @param FeedEntrySearchCriteria $criteria
     * @return Month[]
     */
    public function findMonthsBySearchCriteria(FeedEntrySearchCriteria $criteria): array
    {
        $qb = $this->createCriteriaQueryBuilder($criteria);
        $qb->select('f.date');

        $datesArray = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);

        /**
         * @var Month[] $resultDates
         */
        $resultDates = [];

        foreach ($datesArray as $dateRow) {
            /**
             * @var \DateTimeImmutable $date
             */
            $date  = $dateRow['date'];
            $index = $date->format('Y-m');


            if (!isset($resultDates[$index])) {
                $resultDates[$index] = new Month(
                    (int) $date->format('Y'),
                    (int) $date->format('m')
                );
            }

            $resultDates[$index]->increment();
        }

        return $resultDates;
    }

    protected function createCriteriaQueryBuilder(FeedEntrySearchCriteria $criteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('f');
        $this->applyCriteriaToQueryBuilder($criteria, $qb, 'f');

        $qb->addOrderBy('f.date', 'DESC');
        $qb->addOrderBy('f.title', 'ASC');
        $qb->addOrderBy('f.newsId', 'DESC');

        if ($criteria->getNewsBoard()) {
            $qb->join('f.feedSource', 'fs');
            $qb->andWhere('fs.newsBoard = :newsBoardId');
            $qb->setParameter('newsBoardId', $criteria->getNewsBoard());
        }

        return $qb;
    }
}
