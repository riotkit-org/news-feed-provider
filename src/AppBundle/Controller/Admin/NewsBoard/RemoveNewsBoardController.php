<?php declare(strict_types=1);

namespace AppBundle\Controller\Admin\NewsBoard;

use AppBundle\Entity\FeedSource;
use AppBundle\Manager\NewsBoardManager;
use AppBundle\ValueObject\Response\EntityNotFoundResponse;
use AppBundle\ValueObject\Response\EntityRemovedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @see FeedSource
 */
class RemoveNewsBoardController extends Controller
{
    public function removeAction(Request $request, string $sourceId)
    {
        $feedSource = $this->getManager()->getRepository()->find($sourceId);

        if (!$feedSource instanceof FeedSource) {
            return new EntityNotFoundResponse('feedsource', $sourceId);
        }

        $this->getManager()->remove($feedSource);
        $this->getManager()->flush($feedSource);

        return new EntityRemovedResponse($feedSource);
    }

    protected function getManager() : NewsBoardManager
    {
        return $this->get(NewsBoardManager::class);
    }
}
