<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\AppEvents;
use AppBundle\Form\Model\FeedEntrySearchCriteria;
use AppBundle\Manager\FeedManager;
use AppBundle\ValueObject\Response\FeedListingResponse;
use AppBundle\ValueObject\Response\SearchFieldsDescriptionResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\{Request, Response};

/**
 * Public feed listing
 */
class FeedListController extends Controller
{
    const MAX_ENTRIES_ALLOWED = 500;

    /**
     * @param Request $request
     * @param string  $boardId
     * @param int     $page
     * @param int     $limit
     *
     * @return Response
     */
    public function searchAction(Request $request, string $boardId, int $page = 1, int $limit = 20) : Response
    {
        $requestData = json_decode($request->getContent(), true);
        $requestData['newsBoard'] = $boardId;

        $criteria = new FeedEntrySearchCriteria($requestData);

        if ($limit < 1 || $limit > self::MAX_ENTRIES_ALLOWED) {
            $limit = self::MAX_ENTRIES_ALLOWED;
        }

        return $this->postProcess(new FeedListingResponse(
            $this->getManager()
                ->getRepository()
                ->findBySearchCriteria($criteria, $page, $limit)
        ));
    }

    /**
     * @return Response
     */
    public function describeSearchAction() : Response
    {
        return new SearchFieldsDescriptionResponse(new FeedEntrySearchCriteria([]));
    }

    /**
     * @return FeedManager
     */
    protected function getManager() : FeedManager
    {
        return $this->get(FeedManager::class);
    }

    /**
     * @param FeedListingResponse $response
     * @return FeedListingResponse
     */
    private function postProcess(FeedListingResponse $response): FeedListingResponse
    {
        $event = new GenericEvent($response);
        $this->get('event_dispatcher')->dispatch(AppEvents::FEED_LIST_POST_PROCESS, $event);

        return $response;
    }
}
