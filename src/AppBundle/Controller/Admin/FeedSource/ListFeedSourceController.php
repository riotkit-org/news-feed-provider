<?php declare(strict_types=1);

namespace AppBundle\Controller\Admin\FeedSource;

use AppBundle\Entity\FeedSource;
use AppBundle\Form\Model\FeedSourceSearchCriteria;
use AppBundle\Manager\FeedSourceManager;
use AppBundle\ValueObject\Response\EntityListingResponse;
use AppBundle\ValueObject\Response\SearchFieldsDescriptionResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @see FeedSource
 */
class ListFeedSourceController extends Controller
{
    public function listAction(Request $request, string $boardId) : Response
    {
        return new EntityListingResponse(
            $this->getManager()
                ->getRepository()
                ->findAllByBoardId($boardId)
        );
    }

    public function searchAction(Request $request, string $boardId) : Response
    {
        $criteria = new FeedSourceSearchCriteria(json_decode($request->getContent(), true));

        return new EntityListingResponse(
            $this->getManager()
                ->getRepository()
                ->findBySearchCriteria($criteria, $boardId)
        );
    }

    public function describeSearchAction() : Response
    {
        return new SearchFieldsDescriptionResponse(new FeedSourceSearchCriteria([]));
    }

    protected function getManager() : FeedSourceManager
    {
        return $this->get(FeedSourceManager::class);
    }
}
