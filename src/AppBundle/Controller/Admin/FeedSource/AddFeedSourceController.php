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
    /**
     * @var string $boardId Per request board id
     */
    protected $boardId;

    public function submitFeedSourceAction(Request $request, string $boardId)
    {
        $this->boardId = $boardId;
        return parent::submitAction($request);
    }

    protected function getFormTypeName() : string
    {
        return FeedSourceFormType::class;
    }

    protected function createEntity(Request $request)
    {
        return new FeedSource();
    }

    protected function decodeRequest(Request $request): array
    {
        $decoded = parent::decodeRequest($request);
        $decoded['newsBoard'] = $this->boardId;

        return $decoded;
    }

    protected function performSave($entity)
    {
        $this->getManager()->store($entity);
    }

    private function getManager() : FeedSourceManager
    {
        return $this->get(FeedSourceManager::class);
    }
}
