<?php declare(strict_types=1);

namespace AppBundle\Controller\Admin\NewsBoard;

use AppBundle\Entity\NewsBoard;
use AppBundle\Form\Type\NewsBoardFormType;
use AppBundle\Manager\NewsBoardManager;
use AppBundle\ValueObject\Response\EntityCreatedResponse;
use AppBundle\ValueObject\Response\ValidationFailedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Creates a new "News Board"
 *
 * @see NewsBoard
 */
class AddNewsBoardController extends Controller
{
    public function submitAction(Request $request)
    {
        $entity = new NewsBoard();
        $form = $this->createForm(NewsBoardFormType::class, $entity);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getManager()->persist($entity);
            $this->getManager()->flush($entity);

            return new EntityCreatedResponse($entity, []);
        }

        return new ValidationFailedResponse($form->getErrors(true, true), 422);
    }

    private function getManager() : NewsBoardManager
    {
        return $this->get(NewsBoardManager::class);
    }
}
