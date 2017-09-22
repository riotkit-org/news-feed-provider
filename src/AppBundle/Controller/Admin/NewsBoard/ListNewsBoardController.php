<?php declare(strict_types=1);

namespace AppBundle\Controller\Admin\NewsBoard;

use AppBundle\Manager\NewsBoardManager;
use AppBundle\ValueObject\Response\EntityListingResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @see NewsBoard
 */
class ListNewsBoardController extends Controller
{
    public function listAction()
    {
        return new EntityListingResponse(
            $this->getManager()
                ->getRepository()
                ->findAll()
        );
    }

    protected function getManager() : NewsBoardManager
    {
        return $this->get(NewsBoardManager::class);
    }
}
