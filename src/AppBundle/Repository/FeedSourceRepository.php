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

    public function findBySearchCriteria(FeedSourceSearchCriteria $criteria)
    {

    }
}
