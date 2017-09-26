<?php declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\FeedSource;
use AppBundle\Form\Model\FeedSourceSearchCriteria;
use Doctrine\ORM\EntityRepository;

/**
 * @method FeedSource|null find($id, $lockMode = null, $lockVersion = null)
 */
class FeedSourceRepository extends EntityRepository
{
    use SearchCriteriaSupporting;

    /**
     * Find all entries that are good to collect
     * data from
     *
     * @return FeedSource[]
     */
    public function findSourcesToCollect() : array
    {
        $qb = $this->createQueryBuilder('f');
        $qb->orderBy('f.lastCollectionDate', 'ASC');
        $qb->addOrderBy('f.id', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param FeedSourceSearchCriteria $criteria
     * @param string $newsBoardId
     *
     * @return FeedSource[]
     */
    public function findBySearchCriteria(FeedSourceSearchCriteria $criteria, string $newsBoardId) : array
    {
        $qb = $this->createQueryBuilder('f');
        $qb->andWhere('f.newsBoard = :newsBoardId');
        $qb->setParameter('newsBoardId', $newsBoardId);

        $this->applyCriteriaToQueryBuilder($criteria, $qb, 'f');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $boardId
     *
     * @return FeedSource[]
     */
    public function findAllByBoardId(string $boardId)
    {
        $qb = $this->createQueryBuilder('f');
        $qb->where('f.newsBoard.id = :boardId');
        $qb->setParameter('boardId', $boardId);

        return $qb->getQuery()->getResult();
    }
}
