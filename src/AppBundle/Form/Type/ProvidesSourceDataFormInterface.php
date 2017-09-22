<?php declare(strict_types=1);

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormInterface;

interface ProvidesSourceDataFormInterface
{
    /**
     * Tells if the implementation is able to handle specific incoming data
     *
     * @param mixed $sourceData
     * @param string $sourceType
     *
     * @return bool
     */
    public function isAbleToHandle($sourceData, string $sourceType) : bool;

    /**
     * Appends a sub-form to the main form
     *
     * @param FormInterface $form
     */
    public function appendToMainForm(FormInterface $form);
}
