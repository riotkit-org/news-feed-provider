<?php declare(strict_types=1);

namespace AppBundle\Manager;

use AppBundle\Entity\NewsBoard;
use AppBundle\Exception\DuplicatedDataException;
use AppBundle\Repository\NewsBoardRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;

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

    /**
     * Allows to assign a custom id, but is not transactional
     *
     * @param NewsBoard $newsBoard
     * @throws DuplicatedDataException
     */
    public function store(NewsBoard $newsBoard)
    {
        // allow to store with a custom id
        if ($newsBoard->getId()) {
            $metadata = $this->entityManager->getClassMetadata(get_class($newsBoard));

            $previousGenerator = $metadata->idGenerator;
            $previousGeneratorType = $metadata->generatorType;

            $metadata->setIdGenerator(new AssignedGenerator());
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

            try {
                $this->persist($newsBoard);
                $this->flush($newsBoard);

            } catch (UniqueConstraintViolationException $exception) {
                $metadata->setIdGenerator($previousGenerator);
                $metadata->setIdGeneratorType($previousGeneratorType);
                throw new DuplicatedDataException('Duplicated record "' . $newsBoard->getId() . '"', 0, $exception);
            }

            $metadata->setIdGenerator($previousGenerator);
            $metadata->setIdGeneratorType($previousGeneratorType);
            return;
        }

        $this->persist($newsBoard);
        $this->flush($newsBoard);
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
