<?php declare(strict_types=1);

namespace AppBundle\Controller\Admin\FeedSource;

use AppBundle\Entity\FeedSource;
use AppBundle\Manager\FeedSourceManager;
use AppBundle\ValueObject\Response\EntityListingResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @see FeedSource
 */
class ListFeedSourceController extends Controller
{
    public function listAction() : Response
    {
        return new EntityListingResponse(
            $this->getManager()
                ->getRepository()
                ->findAll()
        );
    }

    public function searchAction() : Response
    {
        $this->getManager()
            ->getRepository()
            ->findBySearchCriteria($criteria);
    }

    protected function getManager() : FeedSourceManager
    {
        return $this->get(FeedSourceManager::class);
    }
}
