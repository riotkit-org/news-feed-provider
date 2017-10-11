<?php declare(strict_types=1);

namespace AppBundle\Manager;

use AppBundle\AppEvents;
use AppBundle\Entity\FeedSource;
use AppBundle\Exception\DuplicatedDataException;
use AppBundle\Repository\FeedSourceRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @see FeedSource
 */
class FeedSourceManager
{
    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * @var FeedSourceRepository $repository
     */
    protected $repository;

    /**
     * @var EventDispatcherInterface $dispatcher
     */
    protected $dispatcher;

    /**
     * @param EntityManager            $em
     * @param FeedSourceRepository     $repository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManager $em, FeedSourceRepository $repository, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $em;
        $this->repository    = $repository;
        $this->dispatcher    = $eventDispatcher;
    }

    /**
     * Allows to assign a custom id, but is not transactional
     *
     * @param FeedSource $feedSource
     * @throws DuplicatedDataException
     */
    public function store(FeedSource $feedSource)
    {
        if ($feedSource->getId()) {
            $metadata = $this->entityManager->getClassMetadata(get_class($feedSource));

            $previousGenerator = $metadata->idGenerator;
            $previousGeneratorType = $metadata->generatorType;

            $metadata->setIdGenerator(new AssignedGenerator());
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

            try {
                $this->persist($feedSource);
                $this->flush($feedSource);

            } catch (UniqueConstraintViolationException $exception) {
                $metadata->setIdGenerator($previousGenerator);
                $metadata->setIdGeneratorType($previousGeneratorType);
                throw new DuplicatedDataException('Duplicated record "' . $feedSource->getId() . '"', 0, $exception);
            }

            $metadata->setIdGenerator($previousGenerator);
            $metadata->setIdGeneratorType($previousGeneratorType);
            return;
        }

        $this->persist($feedSource);
        $this->flush($feedSource);
    }

    public function persist(FeedSource $source)
    {
        $this->dispatcher->dispatch(AppEvents::FEED_SOURCE_PRE_PERSIST, new GenericEvent($source));
        $this->entityManager->persist($source);
    }

    public function remove(FeedSource $source)
    {
        $this->entityManager->remove($source);
    }

    public function getRepository() : FeedSourceRepository
    {
        return $this->repository;
    }

    public function flush(FeedSource $source = null)
    {
        $this->entityManager->flush($source);
    }
}
