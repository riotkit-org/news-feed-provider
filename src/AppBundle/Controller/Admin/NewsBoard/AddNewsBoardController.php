<?php declare(strict_types=1);

namespace AppBundle\Controller\Admin\NewsBoard;

use AppBundle\Controller\Admin\EntityFormController;
use AppBundle\Entity\NewsBoard;
use AppBundle\Form\Type\NewsBoardFormType;
use AppBundle\Manager\NewsBoardManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Creates a new "News Board"
 *
 * @see NewsBoard
 */
class AddNewsBoardController extends EntityFormController
{
    protected function getFormTypeName() : string
    {
        return NewsBoardFormType::class;
    }

    protected function createEntity(Request $request)
    {
        return new NewsBoard();
    }

    protected function performSave($entity)
    {
        $this->getManager()->store($entity);
    }

    private function getManager(): NewsBoardManager
    {
        return $this->get(NewsBoardManager::class);
    }
}
