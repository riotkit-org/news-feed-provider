<?php declare(strict_types=1);

namespace AppBundle\Controller\Admin\NewsBoard;

use AppBundle\Entity\NewsBoard;
use AppBundle\Manager\NewsBoardManager;
use AppBundle\ValueObject\Response\EntityNotFoundResponse;
use AppBundle\ValueObject\Response\EntityRemovedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @see NewsBoard
 */
class RemoveNewsBoardController extends Controller
{
    public function removeAction(string $boardId)
    {
        $board = $this->getManager()->getRepository()->find($boardId);

        if (!$board instanceof NewsBoard) {
            return new EntityNotFoundResponse('newsboard', $boardId);
        }

        $this->getManager()->remove($board);
        $this->getManager()->flush($board);

        return new EntityRemovedResponse($board);
    }

    protected function getManager() : NewsBoardManager
    {
        return $this->get(NewsBoardManager::class);
    }
}
