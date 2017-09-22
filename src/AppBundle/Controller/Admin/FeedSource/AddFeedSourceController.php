<?php declare(strict_types=1);

namespace AppBundle\Controller\Admin\FeedSource;

use AppBundle\Controller\Admin\EntityFormController;
use AppBundle\Entity\FeedSource;
use AppBundle\Form\Type\FeedSourceFormType;
use AppBundle\Manager\FeedSourceManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Adds a feed source (FeedSource)
 *
 * @see FeedSource
 */
class AddFeedSourceController extends EntityFormController
{
    protected function getFormTypeName() : string
    {
        return FeedSourceFormType::class;
    }

    protected function createEntity(Request $request)
    {
        return new FeedSource();
    }

    protected function performSave($entity)
    {
        $this->getManager()->persist($entity);
        $this->getManager()->flush($entity);
    }

    private function getManager() : FeedSourceManager
    {
        return $this->get(FeedSourceManager::class);
    }
}
