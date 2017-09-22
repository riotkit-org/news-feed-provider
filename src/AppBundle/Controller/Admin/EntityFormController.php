<?php declare(strict_types=1);

namespace AppBundle\Controller\Admin;

use AppBundle\ValueObject\Response\EntityCreatedResponse;
use AppBundle\ValueObject\Response\ValidationFailedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class EntityFormController extends Controller
{
    public function submitAction(Request $request)
    {
        $entity = $this->createEntity($request);
        $form = $this->createForm($this->getFormTypeName(), $entity);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $this->performSave($entity);

            return $this->createValidResponse($entity);
        }

        $this->onValidationFailure($entity);
        return $this->createValidationFailedResponse($form);
    }

    protected function createValidResponse($entity)
    {
        return new EntityCreatedResponse($entity, []);
    }

    protected function createValidationFailedResponse(FormInterface $form)
    {
        return new ValidationFailedResponse($form->getErrors(true, true), 422);
    }

    abstract protected function getFormTypeName() : string;
    abstract protected function createEntity(Request $request);
    abstract protected function performSave($entity);

    protected function onValidationFailure($entity)
    {
        // nothing
    }
}
