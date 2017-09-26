<?php declare(strict_types=1);

namespace AppBundle\Controller\Admin\FeedEntry;

use AppBundle\Entity\FeedEntry;
use AppBundle\Form\Model\FeedEntrySearchCriteria;
use AppBundle\Manager\FeedManager;
use AppBundle\ValueObject\Response\EntityListingResponse;
use AppBundle\ValueObject\Response\FeedListingResponse;
use AppBundle\ValueObject\Response\SearchFieldsDescriptionResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{
    Request, Response
};

/**
 * @see FeedEntry
 */
class ListFeedEntryController extends Controller
{
    const MAX_ENTRIES_ALLOWED = 500;

    public function searchAction(Request $request, int $page = 1, int $limit = 20) : Response
    {
        $criteria = new FeedEntrySearchCriteria(json_decode($request->getContent(), true));

        if ($limit < 1 || $limit > self::MAX_ENTRIES_ALLOWED) {
            $limit = self::MAX_ENTRIES_ALLOWED;
        }

        return new FeedListingResponse(
            $this->getManager()
                ->getRepository()
                ->findBySearchCriteria($criteria, $page, $limit)
        );
    }

    public function describeSearchAction() : Response
    {
        return new SearchFieldsDescriptionResponse(new FeedEntrySearchCriteria([]));
    }

    protected function getManager() : FeedManager
    {
        return $this->get(FeedManager::class);
    }
}
