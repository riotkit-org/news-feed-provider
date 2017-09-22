<?php declare(strict_types=1);

namespace AppBundle\Manager;

use AppBundle\Entity\NewsBoard;
use AppBundle\Repository\NewsBoardRepository;
use Doctrine\ORM\EntityManager;

/**
 * @see NewsBoard
 */
class NewsBoardManager
{
    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * @var NewsBoardRepository $repository
     */
    protected $repository;

    public function __construct(EntityManager $em, NewsBoardRepository $repository)
    {
        $this->entityManager = $em;
        $this->repository = $repository;
    }

    public function persist(NewsBoard $newsBoard)
    {
        $this->entityManager->persist($newsBoard);
    }

    public function flush(NewsBoard $newsBoard = null)
    {
        $this->entityManager->flush($newsBoard);
    }

    public function remove(NewsBoard $newsBoard)
    {
        $this->entityManager->remove($newsBoard);
    }

    public function getRepository() : NewsBoardRepository
    {
        return $this->repository;
    }
}
