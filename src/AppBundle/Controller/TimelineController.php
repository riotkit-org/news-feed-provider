<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Form\Model\FeedEntrySearchCriteria;
use AppBundle\Manager\FeedManager;
use AppBundle\ValueObject\Response\ObjectListingResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{Request, Response};

class TimelineController extends Controller
{
    /**
     * Lists months with total amount of entries
     * for given search query
     *
     * @param  Request $request
     * @param  string  $boardId
     * @return Response
     */
    public function listMonthsAction(Request $request, string $boardId) : Response
    {
        $requestData = json_decode($request->getContent(), true);
        $requestData['newsBoard'] = $boardId;

        $criteria = new FeedEntrySearchCriteria($requestData);

        return new ObjectListingResponse(
            $this->getManager()
                ->getRepository()
                ->findMonthsBySearchCriteria($criteria)
        );
    }

    protected function getManager() : FeedManager
    {
        return $this->get(FeedManager::class);
    }
}
